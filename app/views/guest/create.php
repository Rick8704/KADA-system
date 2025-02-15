<?php 
    $title = 'Tambah Anggota';
    require_once '../app/views/layouts/header.php';
?>

<div class="container">
    <div class="form-wizard">   
        <div class="row justify-content-center my-5">
            <div class="col-lg-8">
                <div class="card p-4 shadow-lg">               
                    <h1 class="pageTitle text-center mb-4">
                        <i class="bi bi-person-plus-fill me-2"></i>Pendaftaran Anggota
                    </h1>
                    <!-- Step Indicators -->
                    <div class="step-indicator mb-5">
                        <div class="step active" data-step="1">
                            <div class="step-text">Maklumat Pemohon</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-text">Maklumat Pekerjaan</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-text">Maklumat Kediaman</div>
                        </div>
                        <div class="step" data-step="4">                           
                            <div class="step-text">Maklumat Keluarga</div>
                        </div>
                    </div>
                    
                    <form id="membershipForm" action="/guest/store" method="POST" class="row g-3">
                        <!-- Step 1: Personal Information -->
                        <div class="step-content active" data-step="1">
                            <h4 class="mt-3 mb-4 text-success"><i class="bi bi-person-badge me-2"></i>Maklumat Pemohon</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Penuh</label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control" 
                                           required
                                           onkeyup="this.value = this.value.toUpperCase();"
                                           style="text-transform: uppercase;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">No. K/P</label>
                                    <input type="text" 
                                           name="ic_no" 
                                           class="form-control" 
                                           maxlength="14" 
                                           oninput="formatIC(this)" 
                                           onchange="calculateAgeAndBirthday(this.value.replace(/\D/g, ''))" 
                                           placeholder="e.g., 880101-01-1234" 
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Tarikh Lahir</label>
                                    <input type="date" 
                                           name="birthday" 
                                           id="birthday" 
                                           class="form-control" 
                                           readonly 
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Umur</label>
                                    <input type="number" 
                                           name="age" 
                                           id="age" 
                                           class="form-control" 
                                           readonly 
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Jantina</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="Male">Lelaki</option>
                                        <option value="Female">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Agama</label>
                                    <select name="religion" class="form-select" required>
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Kristian">Kristian</option>
                                        <option value="Others-Religion">Lain-lain</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Bangsa</label>
                                    <select name="race" class="form-select" required>
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="Malay">Melayu</option>
                                        <option value="Chinese">Cina</option>
                                        <option value="Indian">India</option>
                                        <option value="Others-Race">Lain-lain</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Status Perkahwinan</label>
                                    <select name="marital_status" class="form-select" required>
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="Single">Bujang</option>
                                        <option value="Married">Kahwin</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">E-mel</label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control" 
                                           placeholder="contoh@email.com"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Employment Details -->
                        <div class="step-content" data-step="2">
                            <h4 class="mt-3 mb-4 text-success"><i class="bi bi-briefcase me-2"></i>Maklumat Pekerjaan</h4>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Gaji Bulanan (RM)</label>
                                    <input type="text" name="monthly_salary" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Jawatan</label>
                                    <input type="text" name="position" class="form-control" required oninput="this.value = this.value.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Gred</label>
                                    <div class="grade-search-container">
                                        <input type="text" 
                                               name="grade" 
                                               id="gradeInput" 
                                               class="form-control" 
                                               placeholder="Klik untuk pilih gred..." 
                                               required 
                                               readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Contact Information -->
                        <div class="step-content" data-step="3">
                            <h4 class="mt-3 mb-4 text-success"><i class="bi bi-house me-2"></i>Maklumat Kediaman</h4>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Alamat Rumah</label>
                                    <textarea name="home_address" 
                                              id="home_address"
                                              class="form-control" 
                                              rows="2" 
                                              required 
                                              oninput="this.value = this.value.toUpperCase(); detectAddressDebounced('home', this.value);"
                                              style="text-transform: uppercase;"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Poskod</label>
                                    <input type="text" 
                                           name="home_postcode" 
                                           id="home_postcode"
                                           class="form-control" 
                                           required 
                                           pattern="\d{5}"
                                           maxlength="5">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Negeri</label>
                                    <select name="home_state" 
                                            id="home_state"
                                            class="form-select" 
                                            required>
                                        <option value="" disabled selected>Pilih Negeri</option>
                                        <option value="JOHOR">JOHOR</option>
                                        <option value="KEDAH">KEDAH</option>
                                        <option value="KELANTAN">KELANTAN</option>
                                        <option value="MELAKA">MELAKA</option>
                                        <option value="NEGERI SEMBILAN">NEGERI SEMBILAN</option>
                                        <option value="PAHANG">PAHANG</option>
                                        <option value="PERAK">PERAK</option>
                                        <option value="PERLIS">PERLIS</option>
                                        <option value="PULAU PINANG">PULAU PINANG</option>
                                        <option value="SABAH">SABAH</option>
                                        <option value="SARAWAK">SARAWAK</option>
                                        <option value="SELANGOR">SELANGOR</option>
                                        <option value="TERENGGANU">TERENGGANU</option>
                                        <option value="KUALA LUMPUR">KUALA LUMPUR</option>
                                        <option value="LABUAN">LABUAN</option>
                                        <option value="PUTRAJAYA">PUTRAJAYA</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">No. Telefon Bimbit</label>
                                    <input type="tel" name="mobile_phone" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">No. Telefon Rumah</label>
                                    <input type="tel" name="home_phone" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control" required>
                                </div>
                                <h4 class="mt-4 mb-3 text-success"><i class="bi bi-building me-2"></i>Alamat</h4>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Alamat Pejabat</label>
                                    <textarea name="office_address" 
                                              id="office_address"
                                              class="form-control" 
                                              rows="3" 
                                              required 
                                              oninput="this.value = this.value.toUpperCase(); detectAddressDebounced('office', this.value);"
                                              style="text-transform: uppercase;"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Poskod</label>
                                    <input type="text" 
                                           name="office_postcode" 
                                           id="office_postcode"
                                           class="form-control" 
                                           required 
                                           pattern="\d{5}"
                                           maxlength="5">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Negeri</label>
                                    <select name="office_state" 
                                            id="office_state"
                                            class="form-select" 
                                            required>
                                        <option value="" disabled selected>Pilih Negeri</option>
                                        <option value="JOHOR">JOHOR</option>
                                        <option value="KEDAH">KEDAH</option>
                                        <option value="KELANTAN">KELANTAN</option>
                                        <option value="MELAKA">MELAKA</option>
                                        <option value="NEGERI SEMBILAN">NEGERI SEMBILAN</option>
                                        <option value="PAHANG">PAHANG</option>
                                        <option value="PERAK">PERAK</option>
                                        <option value="PERLIS">PERLIS</option>
                                        <option value="PULAU PINANG">PULAU PINANG</option>
                                        <option value="SABAH">SABAH</option>
                                        <option value="SARAWAK">SARAWAK</option>
                                        <option value="SELANGOR">SELANGOR</option>
                                        <option value="TERENGGANU">TERENGGANU</option>
                                        <option value="KUALA LUMPUR">KUALA LUMPUR</option>
                                        <option value="LABUAN">LABUAN</option>
                                        <option value="PUTRAJAYA">PUTRAJAYA</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">No. Telefon Pejabat</label>
                                    <input type="tel" name="office_phone" oninput="this.value = this.value.replace(/[^0-9]/g, '')"class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">No. Fax</label>
                                    <input type="tel" name="fax" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Family Information -->
                        <div class="step-content" data-step="4">
                            <h4 class="mt-3 mb-4 text-success"><i class="bi bi-people me-2"></i>Maklumat Keluarga dan Pewaris</h4>
                            <div class="row g-3">
                                <div class="col-12 mb-3">
                                    <div class="family-member-container">
                                        <div class="row family-member mb-3">
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">Hubungan Keluarga</label>
                                                <select name="family_relationship[]" class="form-select" required>
                                                    <option value="" disabled selected>Pilih</option>
                                                    <option value="Spouse">Isteri</option>
                                                    <option value="Husband">Suami</option>
                                                    <option value="Child">Anak</option>
                                                    <option value="Father">Bapa</option>
                                                    <option value="Mother">Ibu</option>
                                                    <option value="Sibling">Adik-beradik</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Nama</label>
                                                <input type="text" name="family_name[]" class="form-control" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">No. K/P atau No. Surat Beranak</label>
                                                <input type="text" 
                                                       name="family_ic[]" 
                                                       class="form-control" 
                                                       maxlength="14" 
                                                       required
                                                       oninput="formatFamilyIC(this)" 
                                                       placeholder="e.g., 880101-01-1234">
                                            </div>

                                            <script>
                                                function formatFamilyIC(input) {
                                                    let value = input.value.replace(/\D/g, '');
                                                    
                                                    if (value.length > 6) {
                                                        value = value.substring(0, 6) + '-' + value.substring(6);
                                                    }
                                                    if (value.length > 9) {
                                                        value = value.substring(0, 9) + '-' + value.substring(9);
                                                    }
                                                    
                                                    input.value = value;

                                                    if (!validateIC(icNumber)) {
                                                        alert('Nombor K/P tidak sah. Sila masukkan tarikh yang sah.');
                                                        document.querySelector('input[name="ic_no"]').value = '';
                                                        document.querySelector('input[name="ic_no"]').focus();
                                                        return false;
                                                    }
                                                    
                                                    // Clear invalid state when user starts typing again
                                                    input.classList.remove('is-invalid');
                                                    const errorMessage = input.nextElementSibling;
                                                    if (errorMessage?.classList.contains('invalid-feedback')) {
                                                        errorMessage.remove();
                                                    }
                                                }

                                                function validateIC(icNumber) {
                                                    // Remove all non-digits
                                                    const cleanIC = icNumber.replace(/\D/g, '');
                                                    
                                                    if (cleanIC.length !== 12) {
                                                        return false;
                                                    }
                                                    
                                                    // Extract year, month, and day
                                                    const year = cleanIC.substring(0, 2);
                                                    const month = cleanIC.substring(2, 4);
                                                    const day = cleanIC.substring(4, 6);
                                                    
                                                    return isValidDate(year, month, day);
                                                }
                                            </script>
                                            
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger remove-family mb-3" style="display: none;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success add-family-member">
                                        <i class="bi bi-plus-circle me-2"></i>Tambah Ahli
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="step-buttons mt-4 d-flex justify-content-between align-items-center">
                            <div>
                                <!-- Back to Home button - only visible in Step 1 -->
                                <a href="/" class="btn btn-outline-secondary back-home" style="min-width: 140px; display: none;">
                                    <i class="bi bi-house-door me-2"></i>Halaman Utama
                                </a>
                                <!-- Previous button -->
                                <button type="button" class="btn btn-secondary prev-step" style="display: none; min-width: 140px;" id="prev-button">
                                    <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                                </button>
                            </div>
    
                            <div>
                                <!-- Next button -->
                                <button type="button" class="btn btn-gradient next-step" style="min-width: 140px;" id="next-button">
                                    Seterusnya<i class="bi bi-arrow-right ms-2"></i>
                                </button>
    
                                <!-- Submit button -->
                                <button type="submit" class="btn btn-gradient submit-form" style="display: none; min-width: 140px;" id="submit-button">
                                    Hantar<i class="bi bi-check-circle ms-2"></i>
                                </button>
                            </div>
                        </div>                                
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grade Selection Modal -->
<div class="modal fade" id="gradeModal" tabindex="-1" aria-labelledby="gradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradeModalLabel">Pilih Gred</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="search-container">
                    <input type="text" 
                           id="gradeSearch" 
                           class="form-control" 
                           placeholder="Cari gred..."
                           autocomplete="off">
                </div>
                <div class="grade-list-container">
                    <select class="form-select" id="gradeSelect" size="10">
                        <option value="" disabled selected>Pilih</option>
                        <option value="VU1">VU1</option>
                        <option value="VU2">VU2</option>
                        <option value="VU3">VU3</option>
                        <option value="VU4">VU4</option>
                        <option value="VU5">VU5</option>
                        <option value="VU6">VU6</option>
                        <option value="VU7">VU7</option>
                        <option value="VK2">VK2</option>
                        <option value="VK3">VK3</option>
                        <option value="VK4">VK4</option>
                        <option value="VK5">VK5</option>
                        <option value="VK6">VK6</option>
                        <option value="VK7">VK7</option>
                        <option value="A54">A54</option>
                        <option value="A52">A52</option>
                        <option value="A48">A48</option>
                        <option value="A44">A44</option>
                        <option value="A41">A41</option>
                        <option value="A40">A40</option>
                        <option value="A38">A38</option>
                        <option value="A36">A36</option>
                        <option value="A29">A29</option>
                        <option value="A28">A28</option>
                        <option value="A26">A26</option>
                        <option value="A22">A22</option>
                        <option value="A20">A20</option>
                        <option value="A19">A19</option>
                        <option value="A13">A13</option>
                        <option value="A14">A14</option>
                        <option value="A12">A12</option>
                        <option value="A11">A11</option>
                        <option value="A4">A4</option>
                        <option value="A3">A3</option>
                        <option value="A1">A1</option>
                        <option value="AA36">AA36</option>
                        <option value="AA22">AA22</option>
                        <option value="AA20">AA20</option>
                        <option value="AA19">AA19</option>
                        <option value="AA13">AA13</option>
                        <option value="AB40">AB40</option>
                        <option value="AB38">AB38</option>
                        <option value="AB36">AB36</option>
                        <option value="AB30">AB30</option>
                        <option value="AB29">AB29</option>
                        <option value="AB26">AB26</option>
                        <option value="AB22">AB22</option>
                        <option value="AB19">AB19</option>
                        <option value="AL54">AL54</option>
                        <option value="AL52">AL52</option>
                        <option value="AL48">AL48</option>
                        <option value="AL44">AL44</option>
                        <option value="AL41">AL41</option>
                        <option value="AT54">AT54</option>
                        <option value="AT53">AT53</option>
                        <option value="AT52">AT52</option>
                        <option value="AT51">AT51</option>
                        <option value="AT48">AT48</option>
                        <option value="AT47">AT47</option>
                        <option value="AT44">AT44</option>
                        <option value="AT43">AT43</option>
                        <option value="AT41">AT41</option>
                        <option value="B54">B54</option>
                        <option value="B53">B53</option>
                        <option value="B52">B52</option>
                        <option value="B51">B51</option>
                        <option value="B48">B48</option>
                        <option value="B47">B47</option>
                        <option value="B44">B44</option>
                        <option value="B43">B43</option>
                        <option value="B42">B42</option>
                        <option value="B41">B41</option>
                        <option value="B40">B40</option>
                        <option value="B39">B39</option>
                        <option value="B38">B38</option>
                        <option value="B37">B37</option>
                        <option value="B32">B32</option>
                        <option value="B31">B31</option>
                        <option value="B30">B30</option>
                        <option value="B29">B29</option>
                        <option value="B28">B28</option>
                        <option value="B27">B27</option>
                        <option value="B26">B26</option>
                        <option value="B25">B25</option>
                        <option value="B22">B22</option>
                        <option value="B21">B21</option>
                        <option value="B19">B19</option>
                        <option value="B20">B20</option>
                        <option value="B14">B14</option>
                        <option value="B13">B13</option>
                        <option value="B11">B11</option>
                        <option value="C54">C54</option>
                        <option value="C52">C52</option>
                        <option value="C48">C48</option>
                        <option value="C44">C44</option>
                        <option value="C41">C41</option>
                        <option value="C40">C40</option>
                        <option value="C38">C38</option>
                        <option value="C32">C32</option>
                        <option value="C29">C29</option>
                        <option value="C28">C28</option>
                        <option value="C26">C26</option>
                        <option value="C22">C22</option>
                        <option value="C19">C19</option>
                        <option value="C14">C14</option>
                        <option value="C11">C11</option>
                        <option value="DG54">DG54</option>
                        <option value="DG52">DG52</option>
                        <option value="DG48">DG48</option>
                        <option value="DG44">DG44</option>
                        <option value="DG42">DG42</option>
                        <option value="DG41">DG41</option>
                        <option value="DG40">DG40</option>
                        <option value="DG38">DG38</option>
                        <option value="DG34">DG34</option>
                        <option value="DG32">DG32</option>
                        <option value="DG29">DG29</option>
                        <option value="DH54">DH54</option>
                        <option value="DH53">DH53</option>
                        <option value="DH52">DH52</option>
                        <option value="DH51">DH51</option>
                        <option value="DH48">DH48</option>
                        <option value="DH47">DH47</option>
                        <option value="DH44">DH44</option>
                        <option value="DH43">DH43</option>
                        <option value="DH42">DH42</option>
                        <option value="DH41">DH41</option>
                        <option value="DH40">DH40</option>
                        <option value="DH39">DH39</option>
                        <option value="DH34">DH34</option>
                        <option value="DH33">DH33</option>
                        <option value="DH32">DH32</option>
                        <option value="DH31">DH31</option>
                        <option value="DH29">DH29</option>
                        <option value="DM54">DM54</option>
                        <option value="DM53">DM53</option>
                        <option value="DM52">DM52</option>
                        <option value="DM51">DM51</option>
                        <option value="DM46">DM46</option>
                        <option value="DM45">DM45</option>
                        <option value="DM41">DM41</option>
                        <option value="DM40">DM40</option>
                        <option value="DM34">DM34</option>
                        <option value="DM32">DM32</option>
                        <option value="DM29">DM29</option>
                        <option value="DS54">DS54</option>
                        <option value="DS53">DS53</option>
                        <option value="DS52">DS52</option>
                        <option value="DS51">DS51</option>
                        <option value="DS45">DS45</option>
                        <option value="DU56">DU56</option>
                        <option value="DU55">DU55</option>
                        <option value="DU54">DU54</option>
                        <option value="DU53">DU53</option>
                        <option value="DU51">DU51</option>
                        <option value="DU51P">DU51P</option>
                        <option value="DUF54">DUF54</option>
                        <option value="DUF53">DUF53</option>
                        <option value="DUF52">DUF52</option>
                        <option value="DUF51">DUF51</option>
                        <option value="DUF45">DUF45</option>
                        <option value="DUG56">DUG56</option>
                        <option value="DUG55">DUG55</option>
                        <option value="DUG54">DUG54</option>
                        <option value="DUG53">DUG53</option>
                        <option value="DUG51">DUG51</option>
                        <option value="DUG51P">DUG51P</option>
                        <option value="DV54">DV54</option>
                        <option value="DV53">DV53</option>
                        <option value="DV52">DV52</option>
                        <option value="DV51">DV51</option>
                        <option value="DV48">DV48</option>
                        <option value="DV47">DV47</option>
                        <option value="DV44">DV44</option>
                        <option value="DV43">DV43</option>
                        <option value="DV42">DV42</option>
                        <option value="DV41">DV41</option>
                        <option value="DV40">DV40</option>
                        <option value="DV39">DV39</option>
                        <option value="DV38">DV38</option>
                        <option value="DV37">DV37</option>
                        <option value="DV36">DV36</option>
                        <option value="DV35">DV35</option>
                        <option value="DV30">DV30</option>
                        <option value="DV29">DV29</option>
                        <option value="DV22">DV22</option>
                        <option value="DV21">DV21</option>
                        <option value="DV19">DV19</option>
                        <option value="E54">E54</option>
                        <option value="E52">E52</option>
                        <option value="E48">E48</option>
                        <option value="E44">E44</option>
                        <option value="E42">E42</option>
                        <option value="E41">E41</option>
                        <option value="E40">E40</option>
                        <option value="E38">E38</option>
                        <option value="E32">E32</option>
                        <option value="E30">E30</option>
                        <option value="E29">E29</option>
                        <option value="E28">E28</option>
                        <option value="E26">E26</option>
                        <option value="E22">E22</option>
                        <option value="E19">E19</option>
                        <option value="E20">E20</option>
                        <option value="E11">E11</option>
                        <option value="F54">F54</option>
                        <option value="F52">F52</option>
                        <option value="F48">F48</option>
                        <option value="F44">F44</option>
                        <option value="F41">F41</option>
                        <option value="F22">F22</option>
                        <option value="F14">F14</option>
                        <option value="F11">F11</option>
                        <option value="FA40">FA40</option>
                        <option value="FA38">FA38</option>
                        <option value="FA32">FA32</option>
                        <option value="FA29">FA29</option>
                        <option value="FT28">FT28</option>
                        <option value="FT26">FT26</option>
                        <option value="FT22">FT22</option>
                        <option value="FT19">FT19</option>
                        <option value="G54">G54</option>
                        <option value="G52">G52</option>
                        <option value="G48">G48</option>
                        <option value="G44">G44</option>
                        <option value="G41">G41</option>
                        <option value="G40">G40</option>
                        <option value="G36">G36</option>
                        <option value="G32">G32</option>
                        <option value="G30">G30</option>
                        <option value="G29">G29</option>
                        <option value="G28">G28</option>
                        <option value="G26">G26</option>
                        <option value="G22">G22</option>
                        <option value="G20">G20</option>
                        <option value="G19">G19</option>
                        <option value="G14">G14</option>
                        <option value="G11">G11</option>
                        <option value="GV54">GV54</option>
                        <option value="GV52">GV52</option>
                        <option value="GV48">GV48</option>
                        <option value="GV44">GV44</option>
                        <option value="GV41">GV41</option>
                        <option value="H28">H28</option>
                        <option value="H26">H26</option>
                        <option value="H22">H22</option>
                        <option value="H19">H19</option>
                        <option value="H18">H18</option>
                        <option value="H16">H16</option>
                        <option value="H14">H14</option>
                        <option value="H11">H11</option>
                        <option value="J54">J54</option>
                        <option value="J52">J52</option>
                        <option value="J48">J48</option>
                        <option value="J44">J44</option>
                        <option value="J41">J41</option>
                        <option value="J40">J40</option>
                        <option value="J38">J38</option>
                        <option value="J36">J36</option>
                        <option value="J29">J29</option>
                        <option value="J28">J28</option>
                        <option value="J26">J26</option>
                        <option value="J22">J22</option>
                        <option value="J19">J19</option>
                        <option value="J14">J14</option>
                        <option value="J11">J11</option>
                        <option value="JA40">JA40</option>
                        <option value="JA38">JA38</option>
                        <option value="JA36">JA36</option>
                        <option value="JA30">JA30</option>
                        <option value="JA29">JA29</option>
                        <option value="JA26">JA26</option>
                        <option value="JA22">JA22</option>
                        <option value="JA19">JA19</option>
                        <option value="KA54">KA54</option>
                        <option value="KA52">KA52</option>
                        <option value="KA48">KA48</option>
                        <option value="KA44">KA44</option>
                        <option value="KA41">KA41</option>
                        <option value="KA40">KA40</option>
                        <option value="KA38">KA38</option>
                        <option value="KA32">KA32</option>
                        <option value="KA29">KA29</option>
                        <option value="KA28">KA28</option>
                        <option value="KA26">KA26</option>
                        <option value="KA24">KA24</option>
                        <option value="KA22">KA22</option>
                        <option value="KA20">KA20</option>
                        <option value="KA19">KA19</option>
                        <option value="KB54">KB54</option>
                        <option value="KB52">KB52</option>
                        <option value="KB48">KB48</option>
                        <option value="KB44">KB44</option>
                        <option value="KB41">KB41</option>
                        <option value="KB40">KB40</option>
                        <option value="KB38">KB38</option>
                        <option value="KB32">KB32</option>
                        <option value="KB29">KB29</option>
                        <option value="KB28">KB28</option>
                        <option value="KB26">KB26</option>
                        <option value="KB24">KB24</option>
                        <option value="KB22">KB22</option>
                        <option value="KB19">KB19</option>
                        <option value="KJ22">KJ22</option>
                        <option value="KJ18">KJ18</option>
                        <option value="KJ16">KJ16</option>
                        <option value="KJ14">KJ14</option>
                        <option value="KJ13">KJ13</option>
                        <option value="KJ10">KJ10</option>
                        <option value="KJ8">KJ8</option>
                        <option value="KJ6">KJ6</option>
                        <option value="KJ4">KJ4</option>
                        <option value="KJ2">KJ2</option>
                        <option value="KJ1">KJ1</option>
                        <option value="KP54">KP54</option>
                        <option value="KP52">KP52</option>
                        <option value="KP48">KP48</option>
                        <option value="KP44">KP44</option>
                        <option value="KP42">KP42</option>
                        <option value="KP41">KP41</option>
                        <option value="KP40">KP40</option>
                        <option value="KP38">KP38</option>
                        <option value="KP32">KP32</option>
                        <option value="KP29">KP29</option>
                        <option value="KP28">KP28</option>
                        <option value="KP26">KP26</option>
                        <option value="KP22">KP22</option>
                        <option value="KP19">KP19</option>
                        <option value="KP18">KP18</option>
                        <option value="KP16">KP16</option>
                        <option value="KP14">KP14</option>
                        <option value="KP11">KP11</option>
                        <option value="KP19 (Khas)">KP19 (Khas)</option>
                        <option value="L54">L54</option>
                        <option value="L52">L52</option>
                        <option value="L48">L48</option>
                        <option value="L44">L44</option>
                        <option value="L41">L41</option>
                        <option value="L40">L40</option>
                        <option value="L38">L38</option>
                        <option value="L32">L32</option>
                        <option value="L29">L29</option>
                        <option value="L28">L28</option>
                        <option value="L26">L26</option>
                        <option value="L22">L22</option>
                        <option value="L19">L19</option>
                        <option value="LA40">LA40</option>
                        <option value="LA38">LA38</option>
                        <option value="LA32">LA32</option>
                        <option value="LA30">LA30</option>
                        <option value="LA29">LA29</option>
                        <option value="LA26">LA26</option>
                        <option value="LA22">LA22</option>
                        <option value="LA19">LA19</option>
                        <option value="LS54">LS54</option>
                        <option value="LS52">LS52</option>
                        <option value="LS48">LS48</option>
                        <option value="LS44">LS44</option>
                        <option value="LS41">LS41</option>
                        <option value="LS40">LS40</option>
                        <option value="LS38">LS38</option>
                        <option value="LS32">LS32</option>
                        <option value="LS29">LS29</option>
                        <option value="LS28">LS28</option>
                        <option value="LS26">LS26</option>
                        <option value="LS22">LS22</option>
                        <option value="LS19">LS19</option>
                        <option value="M54">M54</option>
                        <option value="M52">M52</option>
                        <option value="M48">M48</option>
                        <option value="M44">M44</option>
                        <option value="M41">M41</option>
                        <option value="N54">N54</option>
                        <option value="N52">N52</option>
                        <option value="N48">N48</option>
                        <option value="N44">N44</option>
                        <option value="N41">N41</option>
                        <option value="N40">N40</option>
                        <option value="N36">N36</option>
                        <option value="N32">N32</option>
                        <option value="N30">N30</option>
                        <option value="N29">N29</option>
                        <option value="N28">N28</option>
                        <option value="N26">N26</option>
                        <option value="N22">N22</option>
                        <option value="N19">N19</option>
                        <option value="N18">N18</option>
                        <option value="N16">N16</option>
                        <option value="N14">N14</option>
                        <option value="N11">N11</option>
                        <option value="N19 (Khas)">N19 (Khas)</option>
                        <option value="NP40">NP40</option>
                        <option value="NP36">NP36</option>
                        <option value="NP32">NP32</option>
                        <option value="NP29">NP29</option>
                        <option value="NT40">NT40</option>
                        <option value="NT36">NT36</option>
                        <option value="NT32">NT32</option>
                        <option value="NT30">NT30</option>
                        <option value="NT29">NT29</option>
                        <option value="NT26">NT26</option>
                        <option value="NT22">NT22</option>
                        <option value="NT19">NT19</option>
                        <option value="NT31 (Khas)">NT31 (Khas)</option>
                        <option value="Q54">Q54</option>
                        <option value="Q53">Q53</option>
                        <option value="Q52">Q52</option>
                        <option value="Q51">Q51</option>
                        <option value="Q48">Q48</option>
                        <option value="Q47">Q47</option>
                        <option value="Q44">Q44</option>
                        <option value="Q43">Q43</option>
                        <option value="Q41">Q41</option>
                        <option value="Q40">Q40</option>
                        <option value="Q36">Q36</option>
                        <option value="Q32">Q32</option>
                        <option value="Q29">Q29</option>
                        <option value="Q28">Q28</option>
                        <option value="Q26">Q26</option>
                        <option value="Q22">Q22</option>
                        <option value="Q19">Q19</option>
                        <option value="R24">R24</option>
                        <option value="R22">R22</option>
                        <option value="R19">R19</option>
                        <option value="R16">R16</option>
                        <option value="R14">R14</option>
                        <option value="R12">R12</option>
                        <option value="R11">R11</option>
                        <option value="R9">R9</option>
                        <option value="R8">R8</option>
                        <option value="R6">R6</option>
                        <option value="R4">R4</option>
                        <option value="R3">R3</option>
                        <option value="R1">R1</option>
                        <option value="S54">S54</option>
                        <option value="S53">S53</option>
                        <option value="S52">S52</option>
                        <option value="S51">S51</option>
                        <option value="S48">S48</option>
                        <option value="S47">S47</option>
                        <option value="S44">S44</option>
                        <option value="S43">S43</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="selectGradeBtn">
                    <i class="bi bi-check2 me-1"></i>Pilih
                </button>
            </div>
        </div>
    </div>
</div>

<script src="/js/form-wizard.js"></script>
<script src="/js/registration-form.js"></script>

<?php require_once '../app/views/layouts/footer.php'; ?>
