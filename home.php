<?php 
session_start();
include('db_connect.php');

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT name, surname, location, highest_qualification, skills, additional_skill FROM users WHERE id=$user_id");
$user = $result->fetch_assoc();

// 12 sample jobs
$jobs = [
    ['id'=>1,'title'=>'Software Developer','field'=>'Technology'],
    ['id'=>2,'title'=>'Graphic Designer','field'=>'Design'],
    ['id'=>3,'title'=>'Digital Marketer','field'=>'Marketing'],
    ['id'=>4,'title'=>'Sales Executive','field'=>'Sales'],
    ['id'=>5,'title'=>'Content Writer','field'=>'Writing'],
    ['id'=>6,'title'=>'Data Analyst','field'=>'Data'],
    ['id'=>7,'title'=>'HR Assistant','field'=>'Human Resources'],
    ['id'=>8,'title'=>'Customer Service Rep','field'=>'Support'],
    ['id'=>9,'title'=>'UX Designer','field'=>'Design'],
    ['id'=>10,'title'=>'Business Analyst','field'=>'Business'],
    ['id'=>11,'title'=>'Mobile App Developer','field'=>'Technology'],
    ['id'=>12,'title'=>'Project Coordinator','field'=>'Management'],
];
?>

<!DOCTYPE html>
<html>
<head>
<title>Youth Employment Platform</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #f0f0f0;
}
.header {
    background-color: #0096FF;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.header-left {
    display: flex;
    align-items: center;
}
.header h1 {
    margin: 0 20px 0 0;
    color: white;
}
.header .search-bar {
    display: flex;
    align-items: center;
}
.header .search-bar input[type="text"] {
    padding: 8px;
    border-radius: 6px 0 0 6px;
    border: 1px solid #C0C0C0;
    width: 200px;
}
.header .search-bar button {
    padding: 8px 10px;
    border: none;
    background-color: #C0C0C0;
    color: white;
    border-radius: 0 6px 6px 0;
    cursor: pointer;
}
.header .top-buttons a {
    text-decoration: none;
    color: white;
    background-color: #C0C0C0;
    padding: 8px 12px;
    margin-left: 10px;
    border-radius: 4px;
    display: inline-block;
}
.header .top-buttons a.profile-icon {
    background-color: #C0C0C0;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    text-align: center;
    line-height: 36px;
    font-size: 18px;
}
.header .top-buttons a.logout {
    background-color: red;
}
.container {
    display: flex;
    padding: 20px;
}
.sidebar {
    width: 250px;
    background-color: #d9d9d9;
    padding: 15px;
    margin-right: 20px;
    border-radius: 6px;
}
.sidebar h3 {
    margin-top: 0;
}
.sidebar button {
    background-color: #0096FF;
    border: none;
    color: white;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
    font-size: 15px;
    transition: background 0.2s ease;
}
.sidebar button:hover {
    background-color: #007ae0;
}
.jobs {
    flex: 1;
    margin-left: 20px;
    margin-right: 20px;
}
.job-card {
    background-color: #e6e6e6;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 6px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: transform 0.2s, box-shadow 0.2s, background-color 0.2s;
}
.job-card:hover {
    background-color: #d0e7ff;
    transform: translateY(-3px); 
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); 
}
.job-card h4 {
    margin: 0;
}
.job-card button {
    margin-left: 10px;
    padding: 6px 12px;
    border: none;
    background-color: #0096FF;
    color: white;
    border-radius: 4px;
    cursor: pointer;
}
.ads {
    width: 200px;
    padding: 15px;
    background-color: #d9d9d9;
    border-radius: 6px;
}
.ads h4 {
    text-align: center;
    margin-bottom: 10px;
}
.ads img {
    width: 100%;
    margin-bottom: 5px;
    border-radius: 4px;
}
.ads p {
    font-size: 13px;
    text-align: center;
    margin-bottom: 15px;
}
.welcome {
    text-align: center;
    margin-top: 15px;
    font-weight: bold;
    font-size: 18px;
}
</style>
</head>
<body>

<div class="header">
    <div class="header-left">
        <h1>Youth Employment Platform</h1>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search for jobs...">
            <button>🔍</button>
        </div>
    </div>
    <div class="top-buttons">
        <a href="profile.php" class="profile-icon">👤</a>
        <a href="premium.php">Premium Membership</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="welcome">
    Welcome, <?php echo htmlspecialchars($user['name']); ?>!
</div>

<div class="container">
    <div class="sidebar">
        <h3>Your Profile</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($user['location']); ?></p>
        <p><strong>Qualification:</strong> <?php echo htmlspecialchars($user['highest_qualification']); ?></p>
        <p><strong>Skills:</strong> <?php echo htmlspecialchars($user['skills']); ?></p>
        <p><strong>Additional Skill:</strong> <?php echo htmlspecialchars($user['additional_skill']); ?></p>

        <!-- New Recommendations Button -->
        <button onclick="window.location.href='recommendations.php'">📘 View Skill Recommendations</button>
    </div>

    <div class="jobs" id="jobList">
        <?php foreach($jobs as $job): ?>
        <div class="job-card">
            <div>
                <h4 class="job-title"><?php echo $job['title']; ?> (<?php echo $job['field']; ?>)</h4>
            </div>
            <div>
                <button onclick="window.location.href='apply.php?job_id=<?php echo $job['id']; ?>'">Apply</button>
                <button onclick="window.location.href='ai_chat.php?job=<?php echo $job['id']; ?>'">Interview Tips</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="ads">
        <h4><b>Sponsored Ads</b></h4>
        <img src="ad1.jpg" alt="Ad ">
        <p><strong>Boost your career with free online IT courses.</strong></p>
        <img src="ad2.jpg" alt="Ad ">
        <p><strong>Get professional CV writing help from experts.</strong></p>
        <img src="ad3.jpg" alt="Ad ">
        <p><strong>Explore remote work opportunities worldwide.</strong></p>
    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const jobList = document.getElementById('jobList');
const jobCards = jobList.getElementsByClassName('job-card');

searchInput.addEventListener('keyup', function() {
    const filter = searchInput.value.toLowerCase();
    for (let i = 0; i < jobCards.length; i++) {
        const title = jobCards[i].getElementsByClassName('job-title')[0].innerText.toLowerCase();
        jobCards[i].style.display = title.indexOf(filter) > -1 ? "" : "none";
    }
});
</script>

</body>
</html>
