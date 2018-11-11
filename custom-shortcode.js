if ($(".molecular").length > 0) {
	var elems = document.querySelectorAll(".molecular__molecule"),
		innerCircle = document.querySelectorAll(".molecular__innercircle")[0],
		paragraphs = document.querySelectorAll(".molecular__paragraph"),
		currentSlide = 0,
		rootCssVariables = getComputedStyle(document.getElementsByClassName("molecular")[0]);
		slideInterval = function() {
			nextMoleculeInterval = setInterval(nextMolecule, 5000);
	};

	function molecularInit() {
		slideInterval();
		elems.forEach(function(el,i) {
			el.addEventListener("mouseover", function(){
				goToMolecule(i);
				clearInterval(nextMoleculeInterval);
			});
			el.addEventListener("mouseout", slideInterval);
			el.addEventListener("click", function(e) {
				e.preventDefault();
			});
		});
	}

	function appendContentToTheMiddleCircle(content) {
		innerCircle.innerHTML = content;
	}

	function clearContentFromMiddleCircle() {
		innerCircle.innerHTML = "";
	}

	function changeCenterCircleBackgroundImage(backgroundUrl) {
		innerCircle.style.background = "";
		clearContentFromMiddleCircle();
		innerCircle.style.backgroundImage = "url("+backgroundUrl+")";
	}

	function changeCenterCircleBackgroundImageWithOverlay(backgroundUrl, color, opacity) {
		console.log("The Image Passed in: "+backgroundUrl);
		console.log("The Color Passed in: "+color);
		console.log("The Opacity Passed in: "+opacity);
		innerCircle.style.backgroundImage = "";
		var rgb = hexToRgb(color);
		console.log("HexToRgbReturned: "+rgb);
		innerCircle.style.background = "linear-gradient(rgba("+rgb+","+opacity+"), rgba("+rgb+","+opacity+")), url("+backgroundUrl+") no-repeat center center";
	}

	function nextMolecule() {
		elems[currentSlide].className = 'molecular__molecule';
		currentSlide = (currentSlide + 1) % elems.length;
		elems[currentSlide].className = 'molecular__molecule molecular__molecule--selected';
		changeCenterCircleBackgroundImage(elems[currentSlide].dataset.url);
	}

	function goToMolecule(m) {
		elems[currentSlide].className = 'molecular__molecule';
		currentSlide = (m + elems.length) % elems.length;
		elems[currentSlide].className = 'molecular__molecule molecular__molecule--selected';
		changeCenterCircleBackgroundImageWithOverlay(elems[currentSlide].dataset.url, rootCssVariables.getPropertyValue("--molecularColor"), rootCssVariables.getPropertyValue("--molecularCenterCircleOpacity"));
		appendContentToTheMiddleCircle(paragraphs[currentSlide].innerHTML);
	}

	function hexToRgb(hex) {
		var r = parseInt(hex.slice(1, 3), 16),
        g = parseInt(hex.slice(3, 5), 16),
        b = parseInt(hex.slice(5, 7), 16);

		return r + "," + g + "," + b;
	}

	molecularInit();
}