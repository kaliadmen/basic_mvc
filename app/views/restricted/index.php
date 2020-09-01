<?php $this->set_site_title('Access Restricted'); ?>
<?php $this->start('head'); ?>
<style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Consolas, serif;
    }

    .error{
        min-height: 100vh;
        background: linear-gradient(0deg, #ffffff, #03a9f4);
    }

    .sky{
        position: relative;
        widows: 100%;
        height: 60vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .sky h2 {
        font-size: 16em;
        color: #fff;
        text-shadow: 15px 15px 0 rgba(0, 0, 0, 0.1);
    }

    .sky h2 span{
        display: inline-block;
        animation: bounce 2s ease-in-out infinite;
    }

    .sky h2 span:nth-child(even){
        animation-delay: -1s;
    }

    .field{
        padding: 100px;
        height: 40vh;
        background: #6e2308;
        box-shadow: inset 0 20px 10px #51680c;
        text-align: center;
    }

    .field h2{
        color: #ffffff;
        font-size: 2em;
        margin-bottom: 20px;
    }

    .field a {
        background: #fff;
        color: #000;
        width: 160px;
        height: 50px;
        line-height: 50px;
        border-radius: 50px;
        display: inline-block;
        text-decoration: none;
        font-size: 20px;
    }

    .field a:hover{
        background: #e0e0e0;
        color: #242424;
    }

    .plane{
        position: absolute;
        bottom: 100px;
        right: 100px;
        max-width: 300px;
    }

    .grass{
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 20px;
        background: url("img/grass.png") bottom;
        animation: travel 0.9s linear infinite;
    }

    @keyframes bounce {
        0%,100%{
            transform: translateY(-50px);
        }
        50%{
            transform: translateY(50px);
        }
    }

    @keyframes travel {
        0%{
            background-position: 0 0;
        }
        100%{
            background-position: 94 0;
        }
    }
</style>
<?php $this->end(); ?>

<?php $this->start('body'); ?>
    <div class="error">
        <div class="sky">
            <h2><span>4</span><span>0</span><span>4</span></h2>
            <div class="grass"></div>
            <img src="css/img/5a37031a715511.8527625615135547144642.png" alt="" class="plane">
        </div>
        <div class="field">
            <h2>Opps...what you were looking for is not here.</h2>
            <a href="<?=PROJECTROOT?>">Go back</a>
        </div>
    </div>
<?php $this->end(); ?>
