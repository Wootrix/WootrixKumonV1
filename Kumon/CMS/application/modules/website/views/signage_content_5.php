</head>

<div style="width: 1920px; height: 1080px;">

    <div style="float:left; width: 1920px; height: 1080px;">

        <?php if (!empty($result[0]['video_path'])): ?>

            <div class="videoTest">

                <video id="vid" autoplay>
                    <source src="<?php echo $this->config->base_url() . 'assets/Article/' . $result[0]['video_path']; ?>"
                            type="video/mp4"/>
                    Your browser does not support the video tag.
                </video>

            </div>

        <?php else: ?>

            <div class="image">

                <div class="bottomText">

                    <div style="margin-left: 10px;">

                        <h1><?php echo $result[0]["title"]; ?></h1>

                        <br/>

                        <h2><?php echo $result[0]["publish_date"]; ?></h2>

                        <br/>

                        <span style="font-size: 40px;"><?php echo substr($result[0]['description_without_html'], 0, 450) . ""; ?>
                            ...</span>

                    </div>
                </div>

            </div>

        <?php endif; ?>

    </div>

</div>







