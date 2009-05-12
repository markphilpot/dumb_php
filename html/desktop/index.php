<?php
/*
 * Created on May 12, 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
?>
<html>
<head>
	<title>Duke University Marching Band Login</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Cache-Control" content="no-store"/>
    <meta http-equiv="Cache-Control" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    
    <link rel="stylesheet" type="text/css" href="css/init.css"></link>
    
</head>
<body>
<div id="loading-mask" class="ext-el-mask" style="width: 100%; height: 100%; background-color: rgb(133, 133, 125); background-repeat: repeat; position: absolute; z-index: 20000; left: 0pt; top: 0pt;">&nbsp;</div>
    <div id="loading" class="x-box-blue" style="width: 320px;">
        <div class="x-box-tl">
            <div class="x-box-tr">
                <div class="x-box-tc">
                </div>
            </div>
        </div>

        <div class="x-box-ml">
            <div class="x-box-mr">
                <div class="x-box-mc">
                    <table>
                        <tbody><tr>
                            <td>
                                <span style="font-weight: bold; color: rgb(23, 56, 91);">Duke University Marching Band Desktop</span>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <div class="loading-indicator">
                                    <table>
                                        <tbody><tr>
                                            <td>
                                                <img alt="loading" src="images/spinner.gif" style="width: 32px; height: 32px; vertical-align: middle;">
                                            </td>

                                            <td>&nbsp;&nbsp;</td>
                                            <td>
                                                <span id="loading-msg" style="text-align: left; color: rgb(23, 56, 91);">Loading...</span>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                            </td>

                        </tr>
                    </tbody></table>
                </div>
            </div>
        </div>
        <div class="x-box-bl">
            <div class="x-box-br">
                <div class="x-box-bc">
                </div>

            </div>
        </div>
    </div>
    
    <div id="login-win" class="x-hidden"></div>
    
    <!-- Begin Loading Sequence -->
    <script type="text/javascript">document.getElementById('loading-msg').innerHTML = 'Loading Libraries...';</script>
		<link rel="stylesheet" href="../extjs/resources/css/ext-all.css"></link>
		<link id="xtheme" rel="stylesheet" href="../extjs/resources/css/xtheme-gray.css"></link>
		<script type="text/javascript" src="../extjs/adapter/ext/ext-base.js"></script>
		<script type="text/javascript" src="../extjs/ext-all.js"></script>

    <script type="text/javascript">document.getElementById('loading-msg').innerHTML = 'Loading Application...';</script>
		<script type="text/javascript" src="js/login.js"></script>

    <script type="text/javascript">document.getElementById('loading-msg').innerHTML = 'Loading Styles...';</script>

</body>
</html>
