<div id="sidebar" class="sidebar" style="border-left: 1px solid #E4E4E7; padding: 30px 30px; width: 400px">
    <p class="text-list" style="font-size: 18px; font-weight: 600; font-size: 20px">Task List</p>
    <div class="progress-container">
        <div class="progress-bar" id="myProgressBar"></div>
    </div>
    <div class="progress-text" id="progressText">0%</div>
    <ul class="list">
        <li class="list-item" onclick="toggleItem(this)">
            <img class="list-item-icon" src="{!! url("images/down-arrow.png"); !!}" style="height: 24px">
            <span class="list-item-title">Persiapan Belajar</span>
        </li>
        <div class="expandable-content">

            <div style="display: flex; flex-direction: column; align-items: left;">

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text" id="requirement">Persetujuan Hak Cipta</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <input type="radio" name="itemSelection" value="item2">
                        </label>
                    </div>
                    <div class="col">
                        <p class="text" id="description">Mekanisme Belajar</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text" id="resource">Forum Diskusi</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text" id="cara_belajar_pemrograman">Cara Belajar Pemrograman</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text" id="glosarium">Glosarium</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text" id="daftar_referensi">Daftar Referensi</p>
                    </div>
                </div>

            </div>

        </div>

        <li class="list-item" onclick="toggleItem(this)">
           <img class="list-item-icon" src="{!! url("images/down-arrow.png"); !!}" style="height: 24px">
            <span class="list-item-title">Pengenalan PHP</span>
        </li>
        <div class="expandable-content">
            <div style="display: flex; flex-direction: column; align-items: left;">

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">Pengantar PHP</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <input type="radio" name="itemSelection" value="item2">
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">Tools</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">Membangun Project PHP</p>
                    </div>
                </div>

            </div>

        </div>

        <li class="list-item" onclick="toggleItem(this)">
           <img class="list-item-icon" src="{!! url("images/down-arrow.png"); !!}" style="height: 24px">
            <span class="list-item-title">PHP Dasar</span>
        </li>
        <div class="expandable-content">
            <div style="display: flex; flex-direction: column; align-items: left;">

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">Pengantar PHP Dasar</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <input type="radio" name="itemSelection" value="item2">
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">Struktur Dasar</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">Tipe Data</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">String</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">Operator</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">Input, Proses, dan Output</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">Array</p>
                    </div>
                </div>

            </div>
        </div>

        <li class="list-item" onclick="toggleItem(this)">
           <img class="list-item-icon" src="{!! url("images/down-arrow.png"); !!}" style="height: 24px">
            <span class="list-item-title">Control Flow</span>
        </li>
        <div class="expandable-content">
            <div style="display: flex; flex-direction: column; align-items: left;">

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">01</p>
                    </div>
                </div>

            </div>
        </div>

        <li class="list-item" onclick="toggleItem(this)">
           <img class="list-item-icon" src="{!! url("images/down-arrow.png"); !!}" style="height: 24px">
            <span class="list-item-title">Object-Oriented Programming (OOP)</span>
        </li>
        <div class="expandable-content">
            <div style="display: flex; flex-direction: column; align-items: left;">

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">01</p>
                    </div>
                </div>

            </div>
        </div>

        <li class="list-item" onclick="toggleItem(this)">
           <img class="list-item-icon" src="{!! url("images/down-arrow.png"); !!}" style="height: 24px">
            <span class="list-item-title">Studi Kasus</span>
        </li>
        <div class="expandable-content">
            <div style="display: flex; flex-direction: column; align-items: left;">

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <a href="{!! url("/phpunit/studi-kasus/projects/$page_link"); !!}"
                        style="padding-top: 3px;
                        float: left;
                        color: #000;
                        text-decoration: none;"
                        >Menampilkan data diri dengan tabel</a>
                    </div>
                </div>

            </div>
        </div>


        <li class="list-item" onclick="toggleItem(this)">
           <img class="list-item-icon" src="{!! url("images/down-arrow.png"); !!}" style="height: 24px">
            <span class="list-item-title">Submission Akhir</span>
        </li>
        <div class="expandable-content">
            <div style="display: flex; flex-direction: column; align-items: left;">

                <div class="row">
                    <div class="col-sm-1">
                        <label class="radio-label">
                            <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
</svg>
                        </label>
                    </div>
                    <div class="col">
                        <p class="text">01</p>
                    </div>
                </div>

            </div>
        </div>


    </ul>
</div>