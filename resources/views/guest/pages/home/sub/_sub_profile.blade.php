<section class="aim-profile-section" id="pengembang">
    <div class="container">
        <!-- Heading Section -->
        <div class="aim-profile-heading aim-reveal">
            <span class="aim-section-kicker"><i></i> PEOPLE BEHIND AIMATRIK</span>
            <div class="row align-items-end g-4">
                <div class="col-lg-7">
                    <h2>Informasi <span>Pengembang.</span></h2>
                </div>
                <div class="col-lg-5">
                    <p>
                        Di balik setiap proyek ada sosok hebat yang bekerja dengan penuh
                        dedikasi, kreativitas, dan komitmen tinggi. Mengelola segala proses
                        dan memastikan setiap detail terjaga dengan kualitas terbaik.
                    </p>
                </div>
            </div>
        </div>

        <!-- Grid Cards Profile (Otomatis Center jika hanya 1 profil) -->
        <div class="row g-4 justify-content-center">
            <!-- Profil 1: Developer -->
            <div class="col-lg-6 col-md-6 aim-reveal">
                <article class="aim-profile-card">
                    <div class="aim-profile-image">
                        <img src="{{ asset('assets/images/logo/Foto-IRFAN-WALHIDAYAH.png') }}"
                             alt="Irfan Walhidayah" loading="lazy">
                        <span class="aim-profile-index">PROFILE / 01</span>
                    </div>
                    <div class="aim-profile-content">
                        <span class="aim-profile-role">PLATFORM DEVELOPER</span>
                        <h3>Irfan Walhidayah</h3>
                        <p class="aim-profile-position">
                            Mahasiswa Profesi Pendidikan Insinyur, Universitas Negeri Makassar
                        </p>
                        <p class="aim-profile-position">
                            NIM: 262558054146    
                        </p>
                        <p class="aim-profile-bio">
                            Bertanggung jawab dalam mengembangkan dan mengelola platform ini dengan
                            fokus pada penerapan teknologi kecerdasan buatan untuk menciptakan
                            solusi digital yang efisien, inovatif, dan berdampak.
                        </p>
                        <div class="aim-profile-footer">
                            <span>AIMATRIK TEAM</span>
                            <span>01 <b>↗</b></span>
                        </div>
                    </div>
                </article>
            </div>

            {{-- <!-- Profil 2: Supervisor -->
            <div class="col-lg-6 col-md-6 aim-reveal">
                <article class="aim-profile-card">
                    <div class="aim-profile-image">
                        <img src="{{ asset('guest-assets/images/bg/person-kridanto.jpg') }}"
                             alt="Prof. Ir. Kridanto Surendro" loading="lazy">
                        <span class="aim-profile-index">PROFILE / 02</span>
                    </div>
                    <div class="aim-profile-content">
                        <span class="aim-profile-role">THESIS SUPERVISOR</span>
                        <h3>Prof. Ir. Kridanto Surendro, M.Sc., Ph.D</h3>
                        <p class="aim-profile-position">
                            Pembimbing Tesis
                        </p>
                        <p class="aim-profile-bio">
                            Berperan sebagai pembimbing tesis dalam pengembangan Model Tata Kelola
                            AI di bidang pendidikan tinggi berbasis CMMI. Dengan bimbingan beliau,
                            model ini berhasil diimplementasikan sebagai platform pengukuran yang terstruktur.
                        </p>
                        <div class="aim-profile-footer">
                            <span>ACADEMIC SUPERVISION</span>
                            <span>02 <b>↗</b></span>
                        </div>
                    </div>
                </article>
            </div> --}}
        </div>
    </div>
</section>

<style>
    .aim-profile-section {
        --aim-red: #ed1b2f;
        padding: 100px 0;
        color: #f7f8fa;
        background: #11161c;
        overflow: hidden;
    }

    .aim-profile-heading {
        margin-bottom: 48px;
    }

    .aim-section-kicker {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        color: #929ba5;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .15em;
    }

    .aim-section-kicker i {
        width: 7px;
        height: 7px;
        background: var(--aim-red);
        border-radius: 50%;
    }

    .aim-profile-heading h2 {
        margin: 0;
        font-size: clamp(38px, 5.5vw, 64px);
        font-weight: 800;
        line-height: 1.05;
        letter-spacing: -.055em;
    }

    .aim-profile-heading h2 span {
        color: var(--aim-red);
    }

    .aim-profile-heading p {
        margin: 0;
        color: #aab2bb;
        font-size: 14px;
        line-height: 1.8;
    }

    .aim-profile-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        background: #171c22;
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: 12px;
        transition: border-color .35s ease, transform .35s ease, box-shadow .35s ease;
    }

    .aim-profile-card:hover {
        transform: translateY(-6px);
        border-color: rgba(237, 27, 47, .65);
        box-shadow: 0 15px 35px rgba(0, 0, 0, .4);
    }

    .aim-profile-image {
        position: relative;
        height: 320px;
        overflow: hidden;
        background: #252c34;
    }

    .aim-profile-image::after {
        position: absolute;
        inset: 40% 0 0;
        content: "";
        pointer-events: none;
        background: linear-gradient(to bottom, transparent, rgba(23, 28, 34, .95));
    }

    .aim-profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 25%;
        transition: transform .6s ease;
    }

    .aim-profile-card:hover .aim-profile-image img {
        transform: scale(1.04);
    }

    .aim-profile-index {
        position: absolute;
        z-index: 1;
        bottom: 16px;
        left: 20px;
        color: rgba(255, 255, 255, .85);
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .15em;
    }

    .aim-profile-content {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        padding: 24px 28px 28px;
    }

    .aim-profile-role {
        color: var(--aim-red);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .15em;
    }

    .aim-profile-content h3 {
        margin: 10px 0 8px;
        color: #fff;
        font-size: clamp(20px, 2.2vw, 26px);
        font-weight: 700;
        line-height: 1.25;
        letter-spacing: -.03em;
    }

    .aim-profile-position {
        margin-bottom: 0;
        color: #d0d5da;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.6;
    }

    .aim-profile-bio {
        margin: 16px 0 24px;
        color: #9da6b0;
        font-size: 13px;
        line-height: 1.85;
        flex-grow: 1;
    }

    .aim-profile-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-top: 16px;
        margin-top: auto;
        border-top: 1px solid rgba(255, 255, 255, .1);
        color: #7f8994;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .12em;
    }

    .aim-profile-footer b {
        margin-left: 6px;
        color: var(--aim-red);
        font-size: 14px;
    }

    @media (max-width: 767.98px) {
        .aim-profile-section {
            padding: 70px 0;
        }

        .aim-profile-heading {
            margin-bottom: 36px;
        }

        .aim-profile-image {
            height: 260px;
        }

        .aim-profile-content {
            padding: 20px;
        }
    }
</style>