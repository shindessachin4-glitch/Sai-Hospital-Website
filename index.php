

<!DOCTYPE html>
<html lang="en">
<head>
<title>Page Title</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
     margin: 0;
	 background-color: #e3f2fd;
	 
}

/* Style the header */
.header {

    padding:50px;
   background-image: url("header4.png");
   text-align: center;
   color: white;
}

/* Increase the font size of the h1 element */
.header h1 {
	color: #F0F8FF;
    font-size: 40px;
}

/* NAVBAR STYLING */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #002D62;
    padding: 10px 20px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

/* NAVBAR LINKS */
.navbar a {
    color: #F0F8FF;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

/* NAVBAR LEFT & RIGHT SECTIONS */
.nav-left {
    display: flex;
    gap: 15px;
}

.nav-right {
    display: flex;
    gap: 15px;
}

/* HOVER EFFECT */
.navbar a:hover {
    background-color: white;
    color: #218838;
    border-radius: 5px;
}



/* RESPONSIVE DESIGN */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        text-align: center;
    }

    .nav-left, .nav-right {
        flex-direction: column;
        width: 100%;
    }

    .navbar a {
        width: 100%;
        padding: 12px;
    }
}


/* Column container */
.column {
    float: left;
    width: 33.3%;
    margin-bottom: 16px;
    padding: 0 8px;
}

.card {
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
    margin: 0px;
}
.card,img{
    display:block;
    padding:0px;
    margin-left:auto;
    margin-right:auto;
   margin-bottom:auto;
}


}
/* Main section */
.main {   
    flex: 70%;
    background-color: #002D62;
    color: #F0F8FF;
    padding: 10px;
    text-align: center;
}

/* Fixed Flex Container */
/* Updated Flex Container */
.flex-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 20px;
    background-color: #e3f2fd; /* Light blue background */
}

/* Styling each flex item */
.flex-container > div {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    background:#5694ce;  /* Gradient blue effect */
    color: white;
    font-weight: bold;
    font-size: 18px;
    border-radius: 12px;
    width: 240px;
    height: 150px;
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

/* Hover effect */
.flex-container > div:hover {
    background: linear-gradient(135deg, #218838, #002D62); /* Greenish-blue hover effect */
    transform: translateY(-5px);
    box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.4);
}

/* Text inside flex items */
.flex-container a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
}

/* Text hover effect */
.flex-container a:hover {
    text-decoration: underline;
}

/* Icons inside flex items */
.flex-container i {
    font-size: 24px;
    margin-right: 8px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .flex-container {
        flex-direction: column;
        align-items: center;
    }

    .flex-container > div {
        width: 90%;
        height: 130px;
    }
}

@media (max-width: 480px) {
    .flex-container > div {
        width: 100%;
        height: 110px;
        font-size: 14px;
    }
}



.team {
    background: #e3f2fd;
    padding: 40px;
    text-align: center;
}

.team h3 {
    color: white;
    margin-bottom: 20px;
}

.team .row {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
}

.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    text-align: center;
    width: 280px;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card img {
    width: 120px;
    height: 120px;
    border-radius: 5%;
    margin-top: 15px;
   
}

.container {
    padding: 15px;
}

.container h2 {
    font-size: 20px;
    color: #0076CE;
    margin: 10px 0;
}

.container .title {
    color: #666;
    font-weight: bold;
}

.container address {
    font-style: normal;
    font-size: 14px;
    margin-top: 10px;
}

.button {
    border: none;
    border-radius: 8px;
    padding: 10px;
    background-color: #28a745;
    color: white;
    cursor: pointer;
    width: 80%;
    margin-top: 10px;
    font-size: 16px;
    transition: background 0.3s ease;
}

.button:hover {
    background-color: #218838;
}

/* Responsive Design */
@media (max-width: 768px) {
    .team .row {
        flex-direction: column;
        align-items: center;
    }
}


/* learn more button*/
.bu{
    border-radius:12px;
    outline: 0;
    display: inline-block;
    padding: 8px;
    color: red;
    background-color:#F0F8FF;
    font-weight:bold;
    text-align: center;
   cursor: pointer;
   width: 10%;
   justify-content: center;
}
@media screen and (max-width: 768px) {
    .bu {
        width: 100px; /* Smaller button for tablets */
        font-size: 14px;
        padding: 8px 12px;
		
    }
}

@media screen and (max-width: 480px) {
    .bu {
        width: 80px; /* Even smaller for mobile screens */
        font-size: 12px;
        padding: 8px 12px;
    }
}
.bu:hover {
      background-color: #555;
}



@media screen and (max-width: 650px) {
  .column {
    width: 100%;
    display: block;
  }

}

/* Footer */
.footer {
    background-color: #002D62;
    color: white;
    text-align: center;
    padding: 30px 20px;
}

.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    gap: 20px;
    max-width: 2200px;
    margin: auto;
}

.footer-about, .footer-contact, .footer-links, .footer-social {
    width: 250px;
}

.footer h2 {
    color: #F0F8FF;
    font-size: 20px;
    margin-bottom: 10px;
    border-bottom: 2px solid #F0F8FF;
    display: inline-block;
    padding-bottom: 5px;
}

.footer p, .footer a {
    color: #F0F8FF;
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 10px;
    display: block;
}

.footer-links ul {
    list-style: none;
    padding: 0;
}

.footer-links ul li {
    margin: 8px 0;
}

.footer-links ul li a:hover {
    text-decoration: underline;
}

.footer-social a {
    font-size: 24px;
    margin: 0 10px;
    color: #F0F8FF;
    transition: color 0.3s ease;
}

.footer-social a:hover {
    color: #218838;
}

.footer-bottom {
    margin-top: 20px;
    font-size: 14px;
    border-top: 1px solid white;
    padding-top: 10px;
}
/* Responsive layout - when the screen is less than 700px wide, make the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 700px) {
  .row {   
    flex-direction: column;
  }
}
.consult_img {
    background-color: #e3f2fd;
    border-radius: 5px;
    text-align: center;
    overflow: hidden; /* Prevents overflowing content */
    padding: 20px;
}

/* Apply transition to images inside .consult_img */
.consult_img img {
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Move the image up slightly and add a shadow effect on hover */
.consult_img img:hover {
    transform: scale(1.05); /* Slight zoom effect */
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
}

}

.consult_img,img,h2 {
 font-weight: bold;
  text-align: center;
  
}
hr{
	border:3px solid #002D62;
	border-radius:2px;
}
.hr_doctor{
	border:15px solid #002D62;
	border-radius:10px;
	background-color: #e3f2fd;
}

</style>
</head>
<body>
<marquee bgcolor="#002D62"> 
<h2 style="color:white;">🚑 Sai Hospital , Health is Wealth..!</h2>
</marquee>
<div class="navbar">
    <div class="nav-left">
        <a href="index.php">🏠Home</a>
        <a href="profile.php">👤Profile</a>
        <a href="contact1.html">📞Contact Us</a>
        <a href="appointment.php">📅Book Appointment</a>
        <a href="add_treatment.php">🩺OPD</a>
        <a href="admission.php">🏥IPD</a>
    </div>
    <div class="nav-right">
        <a href="signup.php">📝 Sign Up</a>
        <a href="login.php">🔑 Login</a>
    </div>
</div>

<div class="header">

<h1> SAI HOSPITAL</h1>
<p>Treating the patients,save the life</p>
<p>- A website by sai H<p>
<p><a href="contact1.html"><button class="bu">learn more</button></a></p>
</div>
<div class="about-section">
<div class="consult_img">
<hr>
<h2 style="color:#002D62;"> About Sai Hospital</h2>

	<img src="1.jpg"  height="400px" width="80%"alt="Dr. Sachin">
<br>
<br>
  <h1 style="color:#002D62;"> Sai Hospital </h1>
  <p>Sai foundation doing social service to public.</p>
<h2 style="color:#002D62;">Follow the Treatment and Avoid the Pain !!</h2>
<br>

</div>
<div>
<h2 style="color:#002D62;"> Specialists Doctors </h2>
	<hr class="hr_doctor">
	<br> 
	<div>
<div class="team">
 
    <div class="row">
        <div class="card">
            <img src="20.jpg" alt="Dr.Sham">
            <div class="container">
                <h2>Dr.Patil</h2>
                <p class="title">MBBS</p>
                <p>Passout in 2025 Batch.</p>
                <address>
                    <a href="mailto:patil@gmail.com">patil@gmail.com</a><br> 
                    <a href="tel:9405244704">9405244704</a><br>
                    Sai Hospital, Ranjani
                </address>
                <a href="appointment.php"><button class="button">Book Appointment</button></a>
            </div>
        </div>

        <div class="card">
            <img src="20.jpg" alt="Dr.Wagh">
            <div class="container">
                <h2>Dr.Wagh</h2>
                <p class="title"></p>BHMS
                <p>Passout 2025 Batch</p>
                <address>
                    <a href="mailto:wagh@gmail.com">wagh@gmail.com</a><br> 
                    <a href="tel:9356997303">9356997303</a><br>
                    Sai Hospital, Ranjani
                </address>
                <a href="appointment.php"><button class="button">Book Appointment</button></a>
            </div>
        </div>
		 <div class="card">
            <img src="20.jpg" alt="Dr.Pawar">
            <div class="container">
                <h2>Dr.Pawar</h2>
                <p class="title">BDS</p>
                <p>Passout 2025 Batch</p>
                <address>
                    <a href="mailto:pawaar1234@gmail.com">pawar1234@gmail.com</a><br> 
                    <a href="tel:9356997303">9356997303</a><br>
                    Sai Hospital, Ranjani
                </address>
                <a href="appointment.php"><button class="button">Book Appointment</button></a>
            </div>
        </div>

        <div class="card">
            <img src="20.jpg" alt="Dr.Gore">
            <div class="container">
                <h2>Dr.Gore</h2>
                <p class="title">MD</p>
                <p>Passout of 2025 Batch</p>
                <address>
                    <a href="mailto:gore1234@gmail.com">gore1234@gmail.com</a><br> 
                    <a href="tel:9356997303">9356997303</a><br>
                    Sai Hospital, Ranjani
                </address>
                <a href="appointment.php"><button class="button">Book Appointment</button></a>
            </div>
        </div>
    </div>
	<br>
	<br>
	<hr>
</div>
<div class="consult_img">
<img src="consult.gif" height="300px" width="40%" bgcolor="#002D62" alt="Dr. Sachin">
<h2 style="color:#002D62;">Online Consultation</h2>
<hr>
<div>
<br>
<br>
<div class="main">
    <h1>Hospital Modules</h1>
    <h3>Explore our services</h3>

    <div class="flex-container">    
        <div class="a"> <p> Book your appointment..!<a href="appointment.php">📅 Book Appointment</a></div>
        <div class="a"> <p>Create your account..!<a href="signup.php">📝 Signup</a></div>
        <div class="a"> <p>View your profile..! <a href="profile.php"> Profile</a></div>
        <div class="a"> <p>Emergency..!<a href="contact1.html">🚑 Emergency</a></div>
    </div>
</div>
<div class="consult_img">
<h2 style="color:#002D62;">Hospital Infrastructure & Specialists</h2>

	<img src="hop.png"  height="400px" width="80%"alt="Dr. Sachin">
<br>
<br>

</div>	
<div class="consult_img">
<hr>
<h2 style="color:#002D62;">Neurologist </h2>

	<img src="4.jpg"  height="400px" width="80%"alt="Dr. Sachin">
<br>
<br>
	<div class="consult_img">
<hr>
<h2 style="color:#002D62;">Cardiologist </h2>

	<img src="14.jpg"  height="400px" width="80%"alt="Dr. Sachin">
<br>
<br>	  
<div class="consult_img">
<hr>
<h2 style="color:#002D62;">Gynecologist </h2>

	<img src="6.jpg"  height="400px" width="80%"alt="Dr. Sachin">
<br>
<br>
	
<div class="consult_img">
<hr>
<h2 style="color:#002D62;">Surgery Specialist</h2>

	<img src="10.jpg"  height="400px" width="80%"alt="Dr. Sachin">
<br>
<br>
<div class="consult_img">
<hr>
<h2 style="color:#002D62;">Geneticist </h2>

	<img src="11.jpg"  height="400px" width="80%"alt="Dr. Sachin">
<br>
<br>
	  <div class="consult_img">
<hr>
<h2 style="color:#002D62;">Ambulance</h2>

	<img src="2.jpg"  height="400px" width="80%"alt="Dr. Sachin">
<br>
<br>
<footer class="footer">
    <div class="footer-container">
        <!-- About Us Section -->
        <div class="footer-about">
            <h2>About Sai Hospital</h2>
            <p>Sai Hospital is dedicated to providing top-notch healthcare services with expert medical professionals and advanced facilities. Our mission is to ensure a healthier future for all.</p>
        </div>

        <!-- Contact Section -->
       

        <!-- Quick Links -->
        <div class="footer-links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="index.php">🏠 Home</a></li>
                <li><a href="appointment.php">📅 Book Appointment</a></li>
                <li><a href="profile.php">👤Profile</a></li>
                <li><a href="contact1.html">📞 Contact Us</a></li>
            </ul>
        </div>

        <!-- Social Media Section -->
        <div class="footer-social">
            <h2>Follow Us</h2>
            <a href="#"><i class="fab fa-facebook">facebook</i></a>
            <a href="#"><i class="fab fa-twitter">twitter</i></a>
            <a href="#"><i class="fab fa-instagram">instagram</i></a>
            <a href="#"><i class="fab fa-linkedin">linkedin</i></a>
        </div>
    </div>

    <!-- Copyright Section -->
    <div class="footer-bottom">
        <p>© 2025 Sai Hospital. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>
