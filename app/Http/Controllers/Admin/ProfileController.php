<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\ProfileUpdateRequest;
use App\Models\Asset;
use App\Models\User;
use App\Services\Asset\AssetCommandService;
use App\Services\Asset\AssetQueryService;
use App\Services\User\UserCommandService;
use App\Services\User\UserQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class ProfileController extends Controller
{
    // Constructor
    public function __construct(
        private readonly UserQueryService $userQueryService,
        private readonly AssetCommandService $assetCommandService,
        private readonly AssetQueryService $assetQueryService,
        private readonly UserCommandService $userCommandService
    ) {}

    public function index(Request $request): View
    {
        // Return View
        return view('admin.pages.setting.profile');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Transactional
        try {
            DB::beginTransaction();
            // Cari user
            $user = $this->userQueryService->findUserById(id: Auth::user()->id, checkPrimaryAccount: false);
            // Cek apakah user ada
            if (! isset($user)) {
                throw new Exception(trans('response.error.update', ['data' => 'User', 'error' => 'User tidak ditemukan']));
            }

            // Handle image
            if ($request->hasFile('image')) {
                $this->_handleImage(request: $request, user: $user);
            }

            // Update user
            $this->userCommandService->updateUserProfile(request: $request, user: $user);
            DB::commit();
            Cache::forget("user:{$user->id}:profileImage");

            // Return
            return to_route('admin.setting.profile.index')->with('success', trans('response.success.update', ['data' => 'User Profile']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.setting.profile.index')->with('error', $th->getMessage());
        }
    }

    // Private function here
    private function _handleImage(Request $request, User $user): ?Asset
    {
        // Hapus asset lama
        $oldAssets = $this->assetQueryService->getAllAsset(pathType: User::class, pathId: $user->id);
        foreach ($oldAssets as $asset) {
            Storage::disk('public')->delete($asset->asset_path);
        }

        // Hapus semua asset lama
        $this->assetCommandService->deleteAllAsset(pathType: User::class, pathId: $user->id);

        // Ambil file dari request
        $file = $request->file('image');

        // Validasi ekstensi file
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, $allowedExtensions)) {
            throw new Exception(trans('response.error.update', ['data' => 'User', 'error' => 'File yang diunggah bukan gambar yang valid']));
        }

        // Simpan file ke storage Laravel
        $filePath = $file->store('user-profile', 'public');
        if (! $filePath) {
            throw new Exception(trans('response.error.update', ['data' => 'User', 'error' => 'Foto profil gagal disimpan']));
        }

        // Simpan informasi file ke assetCommandService
        return $this->assetCommandService->storeAsset(pathType: User::class, pathId: $user->id, pathName: $filePath);
    }
}
