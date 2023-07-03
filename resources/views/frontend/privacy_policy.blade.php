@extends('frontend.layout.template')

@section('style')
@stop

@section('content')
<div class="title-other-page margin-other-page">
  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url('{{asset(env('URL_IMAGE').'slider/thumb/slider1.jpg')}}');opacity:0.9">
        <div class="carousel-caption carousel-caption-left">
        <center>
          <h1>PRIVACY POLICY</h1>
          <br>
        </center>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2><strong>Privacy Policy for BCWF</strong></h2>
      <br/><br/>
      <p>At BCWF, accessible from https://bcwf.baledigital.com/, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by BCWF and how we use it.</p>

      <p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us.</p>

      <h2>Log Files</h2>

      <p>BCWF follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services' analytics. The information collected by log files include internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, tracking users' movement on the website, and gathering demographic information. Our Privacy Policy was created with the help of the <a href="https://www.privacypolicyonline.com/privacy-policy-generator/">Privacy Policy Generator</a>.</p>

      <h2>Cookies and Web Beacons</h2>

      <p>Like any other website, BCWF uses 'cookies'. These cookies are used to store information including visitors' preferences, and the pages on the website that the visitor accessed or visited. The information is used to optimize the users' experience by customizing our web page content based on visitors' browser type and/or other information.</p>

      <p>For more general information on cookies, please read <a href="https://www.privacypolicyonline.com/what-are-cookies/">the "Cookies" article from the Privacy Policy Generator</a>.</p>


      <h2>Our Advertising Partners</h2>

      <p>Some of advertisers on our site may use cookies and web beacons. Our advertising partners are listed below. Each of our advertising partners has their own Privacy Policy for their policies on user data. For easier access, we hyperlinked to their Privacy Policies below.</p>

      <ul>
          <li>
              <p>Google</p>
              <p><a href="https://policies.google.com/technologies/ads">https://policies.google.com/technologies/ads</a></p>
          </li>
      </ul>

      <h2>Privacy Policies</h2>

      <P>You may consult this list to find the Privacy Policy for each of the advertising partners of BCWF.</p>

      <p>Third-party ad servers or ad networks uses technologies like cookies, JavaScript, or Web Beacons that are used in their respective advertisements and links that appear on BCWF, which are sent directly to users' browser. They automatically receive your IP address when this occurs. These technologies are used to measure the effectiveness of their advertising campaigns and/or to personalize the advertising content that you see on websites that you visit.</p>

      <p>Note that BCWF has no access to or control over these cookies that are used by third-party advertisers.</p>

      <h2>Third Party Privacy Policies</h2>

      <p>BCWF's Privacy Policy does not apply to other advertisers or websites. Thus, we are advising you to consult the respective Privacy Policies of these third-party ad servers for more detailed information. It may include their practices and instructions about how to opt-out of certain options. </p>

      <p>You can choose to disable cookies through your individual browser options. To know more detailed information about cookie management with specific web browsers, it can be found at the browsers' respective websites. What Are Cookies?</p>

      <h2>Children's Information</h2>

      <p>Another part of our priority is adding protection for children while using the internet. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity.</p>

      <p>BCWF does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you think that your child provided this kind of information on our website, we strongly encourage you to contact us immediately and we will do our best efforts to promptly remove such information from our records.</p>

      <h2>Online Privacy Policy Only</h2>

      <p>This Privacy Policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collect in BCWF. This policy is not applicable to any information collected offline or via channels other than this website.</p>

      <h2>Consent</h2>

      <p>By using our website, you hereby consent to our Privacy Policy and agree to its Terms and Conditions.</p>
    </div>
  </div>
</div>
@stop

@section('script')
<script>
$(document).ready(function () {
    
});
</script>
@stop
