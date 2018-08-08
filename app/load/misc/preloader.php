<style>
    #loader-wrapper {
    	position: fixed;
    	top: 0;
    	left: 0;
    	width: 100%;
    	height: 100%;
    	z-index: 1000;
    }

    #loader {
    	display: block;
    	position: relative;
    	left: 50%;
    	top: 50%;
    	width: 150px;
    	height: 150px;
    	margin: -75px 0 0 -75px;
    	border-radius: 50%;
    	border: 3px solid transparent;
    	border-top-color: #3498db;
    	-webkit-animation: spin 2s linear infinite;
    	animation: spin 2s linear infinite; 
    	z-index: 1001;
    }

    #loader:before {
    	content: "";
    	position: absolute;
    	top: 5px;
    	left: 5px;
    	right: 5px;
    	bottom: 5px;
    	border-radius: 50%;
    	border: 3px solid transparent;
    	border-top-color: #e74c3c;
    	-webkit-animation: spin 3s linear infinite;
    	animation: spin 3s linear infinite;
    }

    #loader:after {
    	content: "";
    	position: absolute;
    	top: 15px;
    	left: 15px;
    	right: 15px;
    	bottom: 15px;
    	border-radius: 50%;
    	border: 3px solid transparent;
    	border-top-color: #f9c922;
    	-webkit-animation: spin 1.5s linear infinite;
    	animation: spin 1.5s linear infinite;
    }

    @-webkit-keyframes spin {
    	0%   { 
    		-webkit-transform: rotate(0deg);
    		-ms-transform: rotate(0deg);
    		transform: rotate(0deg);
    	}
    	100% {
    		-webkit-transform: rotate(360deg);
    		-ms-transform: rotate(360deg);
    		transform: rotate(360deg);
    	}
    }

    @keyframes spin {
    	0%   { 
    		-webkit-transform: rotate(0deg);
    		-ms-transform: rotate(0deg);
    		transform: rotate(0deg);
    	}
    	100% {
    		-webkit-transform: rotate(360deg);
    		-ms-transform: rotate(360deg);
    		transform: rotate(360deg);
    	}
    }

    #loader-wrapper .loader-section {
    	position: fixed;
    	top: 0;
    	width: 51%;
    	height: 100%;
    	background: #E5E9EC;
    	z-index: 1000;
    	-webkit-transform: translateX(0);
    	-ms-transform: translateX(0);
    	transform: translateX(0);
    }

    #loader-wrapper .loader-section.section-left {
    	left: 0;
    }

    #loader-wrapper .loader-section.section-right {
    	right: 0;
    }

    /* Loaded */
    .loaded #loader-wrapper .loader-section.section-left {
    	-webkit-transform: translateX(-100%);
    	-ms-transform: translateX(-100%);
    	transform: translateX(-100%);
    	-webkit-transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);  
    	transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    }

    .loaded #loader-wrapper .loader-section.section-right {
    	-webkit-transform: translateX(100%);
    	-ms-transform: translateX(100%);
    	transform: translateX(100%);

    	-webkit-transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);  
    	transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    }
        
    .loaded #loader {
    	opacity: 0;
    	-webkit-transition: all 0.3s ease-out;  
    	transition: all 0.3s ease-out;
    }

    .loaded #loader-wrapper {
    	visibility: hidden;
    	-webkit-transform: translateY(-100%);
    	-ms-transform: translateY(-100%);
    	transform: translateY(-100%);
    	-webkit-transition: all 0.3s 1s ease-out;  
    	transition: all 0.3s 1s ease-out;
    }

    h1.no-js{
    	z-index: 10001;
        position: relative;
        color: #EEE;
        left: 30%;
        top: 200px;
    	display: none;
    }
</style>

<h1 class="no-js">Please enable javascript to continue...</h1>
<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>

<!-- No javascript -->
<noscript>
	<style>
        div#wrapper{
            display: none;
        }

        h1.no-js{
            display: block;
        }
	</style>
</noscript>

<!-- Remove Preloader -->
<script>
	$(document).ready(function(){
		$('body').addClass('loaded');
	});
</script>