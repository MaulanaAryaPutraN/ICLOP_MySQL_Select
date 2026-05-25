<!DOCTYPE html>
<html lang="en">

<head>
  @extends('partials.header') <!-- Menggunakan bagian header -->
  <link rel="icon" href="./images/logo.png" type="image/png">
</head>

<body>
  <!-- NAVBAR -->
  @include('partials.navbar') <!-- Menggunakan bagian navbar -->

  <!-- CONTENT -->
  <div class="container" style="margin-top: 50px;">
    <div class="row">
      <!-- TEXT -->
      <div class="col-lg-6 content-left">
        <p class="welcome-to-iclop">Welcome To iCLOP</p>
        <p class="where-education" style="line-height:60px ;">Where Your <span class="education">Education</span> Has No
          Limit</p>
        <p class="subtext">iCLOP (intelligent computer assisted programming learning platform)</p>
        <p class="subtext">With our easy-to-follow tutorials and examples, you can learn to code in no time. Learn to
          code by reading tutorials, trying out examples, and writing applications.</p>
      </div>
      <!-- IMAGE -->
      <div class="col-lg-6 content-right">
        <img src="./images/Edeucation.png" alt="Illustration" style="width: 500px; height: auto;">
      </div>
    </div>
  </div>

  <div class="container" style="margin-top: 10px">
    <div class="row">
      <!-- IMAGE -->
      <div class="col-lg-6 content-left">
        <img src="./images/online_virtual_machine.png" alt="Illustration" style="width: 500px; height: auto;">
      </div>
      <!-- TEXT -->
      <div class="col-lg-6 content-right">
        <p class="where-education" style="font-size: 40px;">Online Virtual Machine</p>
        <p style="font-size: 20px; margin-top: 35px;">Make learning an easy process with support for an online virtual
          machine which will relieve you from the hustle of finding the right computer for your learning needs.</p>
      </div>
    </div>

    <div class="container" style="background-color: #FAFAFA; height: 750px; margin-top: 50px; display: flex; flex-direction: column; align-items: center;">
      <p class="where-education" style="font-size: 35px; text-align: center; margin-top: 50px;">Choose What You Want To
        Study</p>
      <p class="popular-languages" style="font-size: 20px; text-align: center;">Begin By Studying Some of The Most
        Popular Programming Languages</p>
      <div class="row" style="margin-top: 50px;">

        <div class="container text-center" style="margin-top: 35px;">
          <div class="row">
            @foreach ($cards as $card)
            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
              <img src="{{ $card['image'] }}" class="card-img-top" style="width: auto; height: 200px;">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $card['title'] }}</h5>
                <div class="row align-items-start">
                  <div class="col-1">
                    <img src="./images/book.png" style="width: 13px; height: 16px;">
                  </div>
                  <div class="col">
                    <p>{{ $card['topics'] }}</p>
                  </div>
                </div>
                <div style="margin-top: auto;">
                  <a href="#" class="btn btn-primary">Start Learning</a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>

      </div>
      <button class="btn btn-primary custom-button-sign-up" style="width: 252px; height: 42px; margin-top: 60px;">Load
        More</button>
    </div>

    <p class="where-education" style="font-size: 40px; text-align: center; margin-top: 50px;">Our Services</p>
    <p style="font-size: 25px; text-align: center; margin-top: 25px; color: #636363;">Make Your Learning
      Experience<br>Extraordinary With The Services We Provide</p>

    <!-- CARD 1 -->
    <div class="container text-center" style="margin-top: 100px;">
      <div class="row">
        @foreach ($cardsData as $card)
        <div class="col" style="width: 430px; height: 440px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
          <div class="container">
            <img src="{{ $card['image'] }}" alt="computer" style="height: 102px; margin-left: 30px; margin-top: 20px;">
            <p style="font-size: 22px; font-weight: 600; color: #34364A; margin-top: 24px;">{{ $card['title'] }}</p>
            <p style="font-size: 18px;">{{ $card['description'] }}<br>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- CARD 2 -->
    <div class="container text-center" style="margin-top: 35px;">
      <div class="row">
        @foreach ($cardsData2 as $card)
        <div class="col" style="width: 430px; height: 440px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
          <div class="container">
            <img src="{{ $card['image'] }}" alt="computer" style="height: 102px; margin-left: 30px; margin-top: 20px;">
            <p style="font-size: 22px; font-weight: 600; color: #34364A; margin-top: 24px;">{{ $card['title'] }}</p>
            <p style="font-size: 18px;">{{ $card['description'] }}<br>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

</body>
<script src="script.js"></script>
<footer>
  @include('partials.footer')
</footer>

</html>