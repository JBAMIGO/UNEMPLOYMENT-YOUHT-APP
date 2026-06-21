<?php
session_start();
include('db_connect.php');

// Check login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user’s skills
$result = $conn->query("SELECT skills, additional_skill FROM users WHERE id=$user_id");
$user = $result->fetch_assoc();
$user_skills = explode(',', strtolower($user['skills'] . ',' . $user['additional_skill']));

// Define course recommendations
$recommendations = [
    [
        'skill' => 'Web Development',
        'message' => 'Improve your web development skills through structured bootcamps and real projects.',
        'links' => [
            ['name' => 'Coding Bootcamps (Stellenbosch)', 'url' => 'https://coding-bootcamps.sun.ac.za/'],
            ['name' => 'Alison Web Dev Course', 'url' => 'https://alison.com/courses/web-development'],
            ['name' => 'Coursera Web Dev', 'url' => 'https://www.coursera.org/browse/computer-science/mobile-and-web-development']
        ]
    ],
    [
        'skill' => 'Communication',
        'message' => 'Strong communication is key for teamwork and leadership roles.',
        'links' => [
            ['name' => 'Alison Communication Skills', 'url' => 'https://alison.com/courses/communication-skills'],
            ['name' => 'Coursera Communication Mastery', 'url' => 'https://www.coursera.org/learn/wharton-communication-skills']
        ]
    ],
    [
        'skill' => 'Project Management',
        'message' => 'Learn how to plan, manage, and deliver projects effectively.',
        'links' => [
            ['name' => 'Fundiconnect Project Management', 'url' => 'https://fundiconnect.co.za/'],
            ['name' => 'Coursera Project Management', 'url' => 'https://www.coursera.org/specializations/project-management'],
            ['name' => 'IQ Academy Project Management', 'url' => 'https://www.iqacademy.ac.za/course/project-management/']
        ]
    ],
    [
        'skill' => 'Data Analysis',
        'message' => 'Develop your ability to collect, analyze, and visualize data insights.',
        'links' => [
            ['name' => 'Alison Data Analytics', 'url' => 'https://alison.com/courses/data-analysis'],
            ['name' => 'Coursera Data Analysis', 'url' => 'https://www.coursera.org/browse/data-science/data-analysis'],
            ['name' => 'IQ Academy Data Analytics', 'url' => 'https://www.iqacademy.ac.za/course/data-analysis/']
        ]
    ]
];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Skill Gap Recommendations</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 40px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .recommendation {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }
        .recommendation:last-child {
            border-bottom: none;
        }
        h3 {
            color: #1a73e8;
        }
        p {
            color: #555;
        }
        .links {
            margin-top: 10px;
        }
        .links a {
            display: inline-block;
            margin: 5px 10px 5px 0;
            text-decoration: none;
            background: #1a73e8;
            color: white;
            padding: 8px 14px;
            border-radius: 8px;
            transition: background 0.3s ease;
            font-size: 14px;
        }
        .links a:hover {
            background: #155ab6;
        }
        .no-gap {
            text-align: center;
            font-size: 18px;
            color: #2c3e50;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recommendations to Close Experience Gaps</h1>
        <?php
        $found_gap = false;

        foreach ($recommendations as $rec) {
            if (!in_array(strtolower($rec['skill']), array_map('trim', $user_skills))) {
                $found_gap = true;
                echo "<div class='recommendation'>";
                echo "<h3>{$rec['skill']}</h3>";
                echo "<p>{$rec['message']}</p>";
                echo "<div class='links'>";
                foreach ($rec['links'] as $link) {
                    echo "<a href='{$link['url']}' target='_blank'>{$link['name']}</a>";
                }
                echo "</div></div>";
            }
        }

        if (!$found_gap) {
            echo "<div class='no-gap'>🎉 Great job! You’re currently skilled in all key areas.</div>";
        }
        ?>
    </div>
</body>
</html>
