<!-- SIDEBAR -->
            <nav class="col-md-2 d-none d-md-block sidebar sidebar-right-shadow">
                <div class="sidebar-sticky" style="margin-top: 20px;">
                    <ul class="nav flex-column">
                        <li class="nav-item" style="margin-bottom: 40px;">
                            <div class="row align-items-start">
                                <div class="col">
                                    <p style="font-weight: 600; font-size: 14px; color: #34364A; margin-left: 15px;">APLAS WEBAPPS</p>
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/logos_android-vertical.png') }}" alt="learning-logo" style="height: 65px;">
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-book" style="margin-top: 12px; margin-left: 15px; color: #676767;" id="learningIcon"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link active" href="{{ url('android23/material') }}" style="color: #34364A;" id="learningLink">Start Learning</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-check-circle" style="margin-top: 12px; margin-left: 15px; color: #676767;" id="validationIcon"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link" href="{{ url('android23/validation') }}" style="color: #34364A;" id="validationLink">Validation Result</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-trophy" style="margin-top: 12px; margin-left: 15px; color: #676767;" id="rankIcon"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link" href="#" onclick="showContent('rank')" style="color: #34364A;" id="rankLink">Top 20 Rank</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-cog" style="margin-top: 12px; margin-left: 15px; color: #676767;" id="settingsIcon"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link" href="#" onclick="showContent('settings')" style="color: #34364A;" id="settingsLink">Settings</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- ------------------------------------------------------------------------------------------ -->