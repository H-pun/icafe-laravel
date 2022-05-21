<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CodePen - A Pen by Bruce Wayne</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
        *,
        *::after,
        *::before {
            box-sizing: border-box;
        }
        
        html {
            background: #000;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }
        
        head {
            display: block;
            position: relative;
            width: 200px;
            margin: 10% auto 0;
            -webkit-animation: shvr 0.2s infinite;
            animation: shvr 0.2s infinite;
        }
        
        head::after {
            content: '';
            width: 20px;
            height: 20px;
            background: #000;
            position: absolute;
            top: 30px;
            left: 25px;
            border-radius: 50%;
            box-shadow: 125px 0 0 #000;
            -webkit-animation: eye 2.5s infinite;
            animation: eye 2.5s infinite;
        }
        
        meta {
            position: relative;
            display: inline-block;
            background: #fff;
            width: 75px;
            height: 80px;
            border-radius: 50% 50% 50% 50%/45px 45px 45% 45%;
            transform: rotate(45deg);
        }
        
        meta::after {
            content: '';
            position: absolute;
            border-bottom: 2px solid #fff;
            width: 70px;
            height: 50px;
            left: 0px;
            bottom: -10px;
            border-radius: 50%;
        }
        
        meta::before {
            bottom: auto;
            top: -100px;
            transform: rotate(45deg);
            left: 0;
        }
        
        meta:nth-of-type(2) {
            float: right;
            transform: rotate(-45deg);
        }
        
        meta:nth-of-type(2)::after {
            left: 5px;
        }
        
        meta:nth-of-type(3) {
            display: none;
        }
        
        body {
            margin-top: 100px;
            text-align: center;
            color: #fff;
        }
        
        body::before {
            content: 'Open in LINE Apps!';
            font-size: 70px;
            font-weight: 700;
            display: block;
            margin-bottom: 10px;
        }
        
        body::after {
            content: 'Got lost? How.....?  why.....?  Ahhhh....';
            color: #1EA7AB;
            width: 120px;
            font-size: 30px;
            overflow: hidden;
            display: inline-block;
            white-space: nowrap;
            -webkit-animation: text-show 2s infinite steps(3);
            animation: text-show 2s infinite steps(3);
        }
        
        @-webkit-keyframes eye {
            0%,
            30%,
            55%,
            90%,
            100% {
                transform: translate(0, 0);
            }
            10%,
            25% {
                transform: translate(0, 20px);
            }
            65% {
                transform: translate(-20px, 0);
            }
            80% {
                transform: translate(20px, 0);
            }
        }
        
        @keyframes eye {
            0%,
            30%,
            55%,
            90%,
            100% {
                transform: translate(0, 0);
            }
            10%,
            25% {
                transform: translate(0, 20px);
            }
            65% {
                transform: translate(-20px, 0);
            }
            80% {
                transform: translate(20px, 0);
            }
        }
        
        @-webkit-keyframes shvr {
            0% {
                transform: translate(1px, 1em);
            }
            50% {
                transform: translate(0, 1em);
            }
            100% {
                transform: translate(-1px, 1em);
            }
        }
        
        @keyframes shvr {
            0% {
                transform: translate(1px, 1em);
            }
            50% {
                transform: translate(0, 1em);
            }
            100% {
                transform: translate(-1px, 1em);
            }
        }
        
        @-webkit-keyframes text-show {
            to {
                text-indent: -373px;
            }
        }
        
        @keyframes text-show {
            to {
                text-indent: -373px;
            }
        }
    </style>

</head>

<body>
    <!-- partial:index.partial.html -->

    <!-- partial -->

</body>

</html>