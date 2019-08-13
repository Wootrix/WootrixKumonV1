<!doctype html>
<html class="body-background">
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="<?php echo $this->config->base_url();?>jss/jquery.latest.js"></script>
    <title></title>

    <script>

        $(document).ready(function () {

            <?php if( !empty($result[0]['video_path']) ): ?>

                var video = document.getElementById("vid");

                video.addEventListener('loadedmetadata', function () {

                    setTimeout(function () {
                        window.location.reload(1);
                    }, video.duration * 1000);

                });

            <?php else: ?>

            setTimeout(function () {
                window.location.reload(1);
            }, 5 * 1000);

            <?php endif; ?>

        });

    </script>

    <style>

        .overlay {
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            color: #000;
            display: block;
            background-color: rgba(0, 0, 0, 0.8);
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            min-height: 100%
            background-color: #000;
        }

        body {
            overflow:hidden;
        }

        #vid{
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translateX(-50%) translateY(-50%);
            transform: translateX(-50%) translateY(-50%);
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1000;
            overflow: hidden;
        }

        .videoTest > video {
            argin-left: auto;
            margin-right: auto;
            display: block;
        }

        .videoTest {
            position:absolute;
            height:100%;
            width:100%;
            overflow: hidden;
        }

        .image {
            position: relative;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            <?php if( !empty( $result[0]['image'] )): ?>
                background-image: url('<?php echo $this->config->base_url() . 'assets/Article/' . $result[0]['image']; ?>');
            <?php else: ?>
                background-image: url('<?php echo $this->config->base_url() . 'images/banner.jpg'; ?>');
            <?php endif; ?>
            width: 100%;
            height: 100%;
        }

        .bottomText {
            position: absolute;
            bottom: 0px;
            left: 0px;
            color: white;
            background-color: rgba(0, 0, 0, 0.8);
        }

        h1 {
            font-weight: bold;
            color: #fff;
            font-size: 50px;
            margin: 0;
        }

        h2 {
            font-weight: bold;
            color: #fff;
            font-size: 40px;
            margin: 0;
        }

        .centered {
            position: relative;
            color: white;
            font-size: 40px;
            margin: 0 auto;
        }

        .center { margin: 0 auto; width: 400px; }

        /*box-sizing: border-box; height: 100vh; width: 100vw*/

    </style>