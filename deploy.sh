#!/usr/bin/env bash

set -Eeuo pipefail

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

APP_SERVICE="${APP_SERVICE:-app}"
BRANCH="${BRANCH:-main}"
PULL="${PULL:-0}"
HEALTHCHECK_URL="${HEALTHCHECK_URL:-https://aimetrik.my.id/up}"
HEALTHCHECK_ATTEMPTS="${HEALTHCHECK_ATTEMPTS:-30}"

echo -e "${YELLOW}=====================================${NC}"
echo -e "${YELLOW}       Aimetrik Laravel Deployment   ${NC}"
echo -e "${YELLOW}=====================================${NC}"

fail() {
    echo -e "${RED}✗ $1${NC}" >&2
    exit 1
}

cd "$(dirname "${BASH_SOURCE[0]}")"

echo -e "\n${YELLOW}[Pre-flight] Checking requirements...${NC}"

[ -f .env ] || fail "No .env found. Configure .env before deploying."
[ -f docker-compose.yml ] || [ -f compose.yml ] || fail "Docker Compose file not found."

command -v docker >/dev/null 2>&1 || fail "Docker is not installed or not in PATH."
command -v curl >/dev/null 2>&1 || fail "curl is not installed or not in PATH."
docker compose version >/dev/null 2>&1 || fail "Docker Compose plugin is unavailable."
docker compose config --quiet || fail "Docker Compose configuration is invalid."

if ! docker compose ps --status running --services | grep -qx "db"; then
    fail "Database service 'db' is not running. Check it before deploying."
fi

echo -e "${GREEN}✓ Pre-flight checks passed.${NC}"

if [ "$PULL" = "1" ]; then
    git rev-parse --is-inside-work-tree >/dev/null 2>&1 || fail "PULL=1 requested, but this is not a Git repository."

    echo -e "\n${YELLOW}[1/6] Stashing local changes...${NC}"
    STASHED=0
    if ! git diff --quiet || ! git diff --cached --quiet || [ -n "$(git ls-files --others --exclude-standard)" ]; then
        git stash push --include-untracked -m "deploy.sh auto-stash $(date '+%Y-%m-%d %H:%M:%S')"
        STASHED=1
    fi

    echo -e "\n${YELLOW}[2/6] Pulling latest changes from origin/${BRANCH}...${NC}"
    if ! git pull --rebase origin "$BRANCH"; then
        echo -e "${RED}✗ Git pull failed.${NC}"
        if [ "$STASHED" = "1" ]; then
            git stash pop || echo -e "${YELLOW}Warning: stash could not be restored automatically.${NC}"
        fi
        exit 1
    fi

    if [ "$STASHED" = "1" ]; then
        echo -e "\n${YELLOW}Restoring local changes...${NC}"
        git stash pop || echo -e "${YELLOW}Warning: stash could not be restored automatically.${NC}"
    fi
else
    echo -e "\n${YELLOW}[1-2/6] Using current working tree (set PULL=1 to pull first).${NC}"
fi

COMMIT="$(git rev-parse --short HEAD 2>/dev/null || echo 'unknown')"
echo -e "Current commit: ${GREEN}${COMMIT}${NC}"

echo -e "\n${YELLOW}[3/6] Building Laravel image...${NC}"
docker compose build "$APP_SERVICE" || fail "Image build failed. Existing containers were not replaced."
echo -e "${GREEN}✓ Laravel image built.${NC}"

echo -e "\n${YELLOW}[4/6] Running database migrations...${NC}"
docker compose run --rm --no-deps "$APP_SERVICE" php artisan migrate --force \
    || fail "Database migration failed. Existing containers were not replaced."
echo -e "${GREEN}✓ Migrations completed.${NC}"

echo -e "\n${YELLOW}Clearing Laravel caches...${NC}"
docker compose run --rm --no-deps "$APP_SERVICE" php artisan optimize:clear \
    || fail "Could not clear Laravel caches."

echo -e "\n${YELLOW}[5/6] Updating containers...${NC}"
# Do not run 'docker compose down'; keep the database and other services running.
docker compose up -d --remove-orphans || fail "Failed to start updated containers."
echo -e "${GREEN}✓ Containers updated.${NC}"

echo -e "\n${YELLOW}[6/6] Waiting for Laravel health check...${NC}"
HEALTHY=0
for _ in $(seq 1 "$HEALTHCHECK_ATTEMPTS"); do
    if curl --fail --silent "$HEALTHCHECK_URL" >/dev/null 2>&1; then
        HEALTHY=1
        break
    fi
    sleep 2
done

if [ "$HEALTHY" -ne 1 ]; then
    echo -e "${RED}✗ Laravel did not become healthy at: ${HEALTHCHECK_URL}${NC}"
    echo -e "${YELLOW}Check logs: docker compose logs --tail=100 app nginx caddy${NC}"
    docker compose ps
    exit 1
fi

echo -e "${GREEN}✓ Laravel is healthy.${NC}"

echo -e "\n${YELLOW}Container status:${NC}"
docker compose ps

echo -e "\n${GREEN}=====================================${NC}"
echo -e "${GREEN}  Deployment Completed Successfully  ${NC}"
echo -e "${GREEN}=====================================${NC}"
echo -e "  Website: ${GREEN}https://aimetrik.my.id${NC}"
echo -e "  Health:  ${GREEN}${HEALTHCHECK_URL}${NC}"
echo -e "  Commit:  ${GREEN}${COMMIT}${NC}"
echo
echo -e "${YELLOW}To inspect application logs:${NC}"
echo "  docker compose logs -f app"
