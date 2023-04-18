const handleThemeUpdate = (cssVars) => {
    const root = document.querySelector(':root');
    const keys = Object.keys(cssVars);
    keys.forEach(key => {
        root.style.setProperty(key, cssVars[key]);
    });
}

// to check the value is hexa or not
const isValidHex = (hexValue) => /^#([A-Fa-f0-9]{3,4}){1,2}$/.test(hexValue)

const getChunksFromString = (st, chunkSize) => st.match(new RegExp(`.{${chunkSize}}`, "g"))
// convert hex value to 256
const convertHexUnitTo256 = (hexStr) => parseInt(hexStr.repeat(2 / hexStr.length), 16)
// get alpha value is equla to 1 if there was no value is asigned to alpha in function
const getAlphafloat = (a, alpha) => {
    if (typeof a !== "undefined") { return a / 255 }
    if ((typeof alpha != "number") || alpha < 0 || alpha > 1) {
        return 1
    }
    return alpha
}
// convertion of hex code to rgba code 
function hexToRgba(hexValue, alpha) {
    if (!isValidHex(hexValue)) { return null }
    const chunkSize = Math.floor((hexValue.length - 1) / 3)
    const hexArr = getChunksFromString(hexValue.slice(1), chunkSize)
    const [r, g, b, a] = hexArr.map(convertHexUnitTo256)
    return `rgba(${r}, ${g}, ${b}, ${getAlphafloat(a, alpha)})`
}

function themeSwitch(switchProperty){
	switchProperty.forEach((item) => {
		item.addEventListener('click', (e) => {
			const primaryColor = e.target.getAttribute('data-bg-color')
			const primaryHoverColor = e.target.getAttribute('data-bg-hover')
			const primaryBorderColor = e.target.getAttribute('data-bg-border')
			const primaryTransparent = e.target.getAttribute('data-bg-transparent')
			const darkPrimary = e.target.getAttribute('data-primary')
			const darkprimaryTransparent = e.target.getAttribute('data-bg-darktransparent')
			const transparentPrimary = e.target.getAttribute('data-primary')
			const transparentBgColor = e.target.getAttribute('data-body')
			const transparentBgTheme = e.target.getAttribute('data-theme')
			const transparentprimaryTransparent = e.target.getAttribute('data-transparentcolor')

			handleThemeUpdate({
				'--primary-bg-color': primaryColor,
				'--primary-bg-hover': primaryHoverColor,
				'--primary-bg-border': primaryBorderColor,
				'--primary-transparentcolor': primaryTransparent,
				'--dark-primary': darkPrimary,
				'--darkprimary-transparentcolor': darkprimaryTransparent,
				'--transparent-primary': transparentPrimary,
				'--transparent-body': transparentBgColor,
				'--transparent-theme': transparentBgTheme,
				'--transparentprimary-transparentcolor': transparentprimaryTransparent,
			});

			$("input.input-color-picker[data-id='bg-color']").val(primaryColor)
			$("input.input-color-picker[data-id1='bg-hover']").val(primaryColor)
			$("input.input-color-picker[data-id2='bg-border']").val(primaryColor)
			$("input.input-color-picker[data-id7='transparentcolor']").val(primaryColor)
			$("input.input-color-picker[data-id3='primary']").val(darkPrimary)
			$("input.input-color-picker[data-id8='transparentcolor']").val(darkPrimary)
			$("input.input-color-picker[data-id4='primary']").val(transparentPrimary)
			$("input.input-color-picker[data-id5='body']").val(transparentBgColor)
			$("input.input-color-picker[data-id6='theme']").val(transparentBgTheme)
			$("input.input-color-picker[data-id9='transparentcolor']").val(transparentPrimary)
		});
	});
}

function dynamicPrimaryColor(primaryColor){
    primaryColor.forEach((item) => {
		item.addEventListener('input', (e) => {
			const cssPropName = `--primary-${e.target.getAttribute('data-id')}`;
			const cssPropName1 = `--primary-${e.target.getAttribute('data-id1')}`;
			const cssPropName2 = `--primary-${e.target.getAttribute('data-id2')}`;
			const cssPropName7 = `--primary-${e.target.getAttribute('data-id7')}`;
			const cssPropName8 = `--darkprimary-${e.target.getAttribute('data-id8')}`;
			const cssPropName3 = `--dark-${e.target.getAttribute('data-id3')}`;
			const cssPropName4 = `--transparent-${e.target.getAttribute('data-id4')}`;
			const cssPropName5 = `--transparent-${e.target.getAttribute('data-id5')}`;
			const cssPropName6 = `--transparent-${e.target.getAttribute('data-id6')}`;
			const cssPropName9 = `--transparentprimary-${e.target.getAttribute('data-id9')}`;
			handleThemeUpdate({
				[cssPropName]: hexToRgba(e.target.value),
				 // 95 is used as the opacity 0.95  
				[cssPropName1]:  hexToRgba(e.target.value, 0.99),
				[cssPropName2]: hexToRgba(e.target.value, 0.2),
				[cssPropName3]: hexToRgba(e.target.value),
				[cssPropName7]: hexToRgba(e.target.value, 0.2),
				[cssPropName8]: hexToRgba(e.target.value, 0.2),
				[cssPropName4]: e.target.value,
				[cssPropName5]: e.target.value,
				[cssPropName6]: e.target.value + 95,
				[cssPropName9]: e.target.value + 20,
			});
		});
	});
}

(function () {
	// Light theme color picker
	const LightThemeSwitchers = document.querySelectorAll('.light-theme .switch_section span');
	const dynamicPrimaryLight = document.querySelectorAll('input.color-primary-light');

    themeSwitch(LightThemeSwitchers);
    dynamicPrimaryColor(dynamicPrimaryLight);

    // dark theme color picker

    const DarkThemeSwitchers = document.querySelectorAll('.dark-theme .switch_section span')
	const DarkDynamicPrimaryLight = document.querySelectorAll('input.color-primary-dark');

    themeSwitch(DarkThemeSwitchers);
    dynamicPrimaryColor(DarkDynamicPrimaryLight);

	// tranparent theme color picker

    const transparentThemeSwitchers = document.querySelectorAll('.transparent-theme .switch_section span')
	const transparentDynamicPrimaryLight = document.querySelectorAll('input.color-primary-transparent');
    
    themeSwitch(transparentThemeSwitchers);
    dynamicPrimaryColor(transparentDynamicPrimaryLight);

	// tranparent theme bgcolor picker

    const transparentBgThemeSwitchers = document.querySelectorAll('.transparent-theme .switch_section span')
	const transparentDynamicPBgLight = document.querySelectorAll('input.color-bg-transparent');
    
    themeSwitch(transparentBgThemeSwitchers);
    dynamicPrimaryColor(transparentDynamicPBgLight);

	localStorageBackup();

	$('#myonoffswitch1').on('click', function(){
		document.querySelector('body')?.classList.remove('dark-theme');
		document.querySelector('body')?.classList.remove('transparent-theme');
		document.querySelector('body')?.classList.remove('bg-img1');
		document.querySelector('body')?.classList.remove('bg-img2');
		document.querySelector('body')?.classList.remove('bg-img3');
		document.querySelector('body')?.classList.remove('bg-img4');
		
		localStorage.removeItem('BgImage');
		$('#myonoffswitch1').prop('checked', true);
	})
	$('#myonoffswitch2').on('click', function(){
	document.querySelector('body')?.classList.remove('light-theme');
	document.querySelector('body')?.classList.remove('transparent-theme');
	document.querySelector('body')?.classList.remove('bg-img1');
	document.querySelector('body')?.classList.remove('bg-img2');
	document.querySelector('body')?.classList.remove('bg-img3');
	document.querySelector('body')?.classList.remove('bg-img4');
	
	localStorage.removeItem('BgImage');
	$('#myonoffswitch2').prop('checked', true);
	})
	$('#myonoffswitchTransparent').on('click', function(){
	document.querySelector('body')?.classList.remove('dark-theme');
	document.querySelector('body')?.classList.remove('light-theme');
	document.querySelector('body')?.classList.remove('bg-img1');
	document.querySelector('body')?.classList.remove('bg-img2');
	document.querySelector('body')?.classList.remove('bg-img3');
	document.querySelector('body')?.classList.remove('bg-img4');
	
	localStorage.removeItem('BgImage');
	$('#myonoffswitchTransparent').prop('checked', true);
	})

})();

function localStorageBackup(){
	// if there is a value stored, update color picker and background color
	// Used to retrive the data from local storage
	if (localStorage.primaryColor) {
		// document.getElementById('colorID').value = localStorage.primaryColor;
		document.querySelector('html').style.setProperty('--primary-bg-color', localStorage.primaryColor);
		document.querySelector('html').style.setProperty('--primary-bg-hover', localStorage.primaryHoverColor);
		document.querySelector('html').style.setProperty('--primary-bg-border', localStorage.primaryBorderColor);
		document.querySelector('html').style.setProperty('--primary-transparentcolor', localStorage.primaryTransparent);
		document.querySelector('body')?.classList.add('light-theme');
		document.querySelector('body')?.classList.remove('dark-theme');
		document.querySelector('body')?.classList.remove('transparent-theme');
		document.querySelector('body')?.classList.remove('bg-img2');
		
		$('#myonoffswitch1').prop('checked', true);

		
	}

	if (localStorage.darkPrimary) {
		// document.getElementById('darkPrimaryColorID').value = localStorage.darkPrimary;
		document.querySelector('html').style.setProperty('--primary-bg-color', localStorage.darkPrimary);
		document.querySelector('html').style.setProperty('--primary-bg-hover', localStorage.darkPrimary);
		document.querySelector('html').style.setProperty('--primary-bg-border', localStorage.darkPrimary);
		document.querySelector('html').style.setProperty('--dark-primary', localStorage.darkPrimary);
		document.querySelector('html').style.setProperty('--darkprimary-transparentcolor', localStorage.darkprimaryTransparent);
		document.querySelector('body')?.classList.add('dark-theme');
		document.querySelector('body')?.classList.remove('light-theme');
		document.querySelector('body')?.classList.remove('transparent-theme');
		document.querySelector('body')?.classList.remove('bg-img2');
		
		$('#myonoffswitch2').prop('checked', true);

	}

	if (localStorage.transparentPrimary) {
		// document.getElementById('transparentPrimaryColorID').value = localStorage.transparentPrimary;
		document.querySelector('html').style.setProperty('--primary-bg-color', localStorage.transparentPrimary);
		document.querySelector('html').style.setProperty('--primary-bg-hover', localStorage.transparentPrimary);
		document.querySelector('html').style.setProperty('--primary-bg-border', localStorage.transparentPrimary);
		document.querySelector('html').style.setProperty('--transparent-primary', localStorage.transparentPrimary);
		document.querySelector('html').style.setProperty('--transparentprimary-transparentcolor', localStorage.transparentprimaryTransparent);
		document.querySelector('body')?.classList.add('transparent-theme');
		document.querySelector('body')?.classList.remove('dark-theme');
		document.querySelector('body')?.classList.remove('light-theme');
		document.querySelector('body')?.classList.remove('bg-img2');
	
		$('#myonoffswitchTransparent').prop('checked', true);
	}

	if (localStorage.transparentBgImgPrimary) {
		// document.getElementById('transparentBgImgPrimaryColorID').value = localStorage.transparentBgImgPrimary;
		document.querySelector('html').style.setProperty('--primary-bg-color', localStorage.transparentBgImgPrimary);
		document.querySelector('html').style.setProperty('--primary-bg-hover', localStorage.transparentBgImgPrimary);
		document.querySelector('html').style.setProperty('--primary-bg-border', localStorage.transparentBgImgPrimary);
		document.querySelector('html').style.setProperty('--transparent-primary', localStorage.transparentBgImgPrimary);
		document.querySelector('html').style.setProperty('--transparentprimary-transparentcolor', localStorage.transparentBgImgprimaryTransparent);
		document.querySelector('body')?.classList.add('transparent-theme');
		document.querySelector('body')?.classList.remove('dark-theme');
		document.querySelector('body')?.classList.remove('light-theme');
		
		$('#myonoffswitchTransparent').prop('checked', true);
	}

    if (localStorage.transparentBgColor) {
		// document.getElementById('transparentBgColorID').value = localStorage.transparentBgColor;
		document.querySelector('html').style.setProperty('--transparent-body', localStorage.transparentBgColor);
		document.querySelector('html').style.setProperty('--transparent-theme', localStorage.transparentThemeColor);
		document.querySelector('html').style.setProperty('--transparentprimary-transparentcolor', localStorage.transparentprimaryTransparent);
		document.querySelector('body')?.classList.add('transparent-theme');
		document.querySelector('body')?.classList.remove('dark-theme');
		document.querySelector('body')?.classList.remove('light-theme');
		document.querySelector('body')?.classList.remove('bg-img2');
	
		$('#myonoffswitchTransparent').prop('checked', true);
	}
	if (localStorage.BgImage) {
		document.querySelector('body')?.classList.add('transparent-theme');
		document.querySelector('body')?.classList.remove('dark-theme');
		document.querySelector('body')?.classList.remove('light-theme');
		if(document.querySelector('body')?.classList.add(localStorage.BgImage) !== "bg-img2"){
			document.querySelector('body').classList.remove('bg-img2')
		}
		document.querySelector('body')?.classList.add(localStorage.BgImage);
		$('#myonoffswitchTransparent').prop('checked', true);
	}
}

function changePrimaryColor() {
    $('#myonoffswitch3').prop('checked', true);
    $('#myonoffswitch6').prop('checked', true);
    checkOptions();

	let userColor = document.getElementById('colorID').value;
	localStorage.setItem('primaryColor', userColor);
	// to store value as opacity 0.95 we use 95
	localStorage.setItem('primaryHoverColor', userColor + 99);
	localStorage.setItem('primaryBorderColor', userColor + 20);
	localStorage.setItem('primaryTransparent', userColor + 20);

	// removing dark theme properties
	localStorage.removeItem('darkPrimary')
	localStorage.removeItem('darkprimaryTransparent');
	localStorage.removeItem('transparentBgColor');
	localStorage.removeItem('transparentThemeColor');
	localStorage.removeItem('transparentPrimary');
	localStorage.removeItem('transparentprimaryTransparent');
    localStorage.removeItem('transparentBgImgPrimary');
	localStorage.removeItem('transparentBgImgprimaryTransparent');
	document.querySelector('body')?.classList.add('light-theme');
	document.querySelector('body')?.classList.remove('dark-theme');
	document.querySelector('body')?.classList.remove('transparent-theme');
	document.querySelector('body')?.classList.remove('bg-img2');
	localStorage.removeItem('BgImage');

	$('#myonoffswitch1').prop('checked', true);
	names()
}

function darkPrimaryColor() {

	$('#myonoffswitch2').prop('checked', true);
	$('#myonoffswitch5').prop('checked', true);
    $('#myonoffswitch8').prop('checked', true);
    checkOptions();
	let userColor = document.getElementById('darkPrimaryColorID').value;
	localStorage.setItem('darkPrimary', userColor);
	localStorage.setItem('darkprimaryTransparent', userColor+20);
	document.querySelector('body')?.classList.add('dark-theme');

    // removing light theme data 
	localStorage.removeItem('primaryColor')
	localStorage.removeItem('primaryHoverColor')
	localStorage.removeItem('primaryBorderColor')
	localStorage.removeItem('primaryTransparent');

	localStorage.removeItem('transparentBgColor');
	localStorage.removeItem('transparentThemeColor');
	localStorage.removeItem('transparentPrimary');
	localStorage.removeItem('transparentprimaryTransparent');
    localStorage.removeItem('transparentBgImgPrimary');
	localStorage.removeItem('transparentBgImgprimaryTransparent');
	localStorage.removeItem('BgImage');

	document.querySelector('body')?.classList.remove('light-theme');
	document.querySelector('body')?.classList.remove('transparent-theme');
	document.querySelector('body')?.classList.remove('bg-img1');
	document.querySelector('body')?.classList.remove('bg-img2');
	document.querySelector('body')?.classList.remove('bg-img3');
	document.querySelector('body')?.classList.remove('bg-img4');

	names();
}

function transparentPrimaryColor() {
	$('#myonoffswitch3').prop('checked', false);
    $('#myonoffswitch6').prop('checked', false);
    $('#myonoffswitch5').prop('checked', false);
    $('#myonoffswitch8').prop('checked', false);

	let userColor = document.getElementById('transparentPrimaryColorID').value;
	localStorage.setItem('transparentPrimary', userColor);
	localStorage.setItem('transparentprimaryTransparent', userColor+20);
    
    // removing light theme data 
	localStorage.removeItem('darkPrimary');
	localStorage.removeItem('darkprimaryTransparent');
	localStorage.removeItem('primaryColor');
	localStorage.removeItem('primaryHoverColor');
	localStorage.removeItem('primaryBorderColor');
	localStorage.removeItem('primaryTransparent');
    localStorage.removeItem('transparentBgImgPrimary');
	localStorage.removeItem('transparentBgImgprimaryTransparent');
	document.querySelector('body').classList.add('transparent-theme');
	document.querySelector('body')?.classList.remove('light-theme');
	document.querySelector('body')?.classList.remove('dark-theme');
	document.querySelector('body')?.classList.remove('bg-img1');
	document.querySelector('body')?.classList.remove('bg-img2');
	document.querySelector('body')?.classList.remove('bg-img3');
	document.querySelector('body')?.classList.remove('bg-img4');

	$('#myonoffswitchTransparent').prop('checked', true);
    checkOptions();
	removeForTransparent();
	names()
}

function transparentBgImgPrimaryColor() {
	$('#myonoffswitch3').prop('checked', false);
    $('#myonoffswitch6').prop('checked', false);
    $('#myonoffswitch5').prop('checked', false);
    $('#myonoffswitch8').prop('checked', false);
	let userColor = document.getElementById('transparentBgImgPrimaryColorID').value;
	localStorage.setItem('transparentBgImgPrimary', userColor);
	localStorage.setItem('transparentBgImgprimaryTransparent', userColor+20);
	if(
		document.querySelector('body')?.classList.contains('bg-img1') == false &&
		document.querySelector('body')?.classList.contains('bg-img2') == false &&
		document.querySelector('body')?.classList.contains('bg-img3') == false &&
		document.querySelector('body')?.classList.contains('bg-img4') == false
		){
		document.querySelector('body')?.classList.add('bg-img1');
		localStorage.setItem('BgImage', 'bg-img1');
	}
    // removing light theme data 
	localStorage.removeItem('darkPrimary');
	localStorage.removeItem('darkprimaryTransparent');
	localStorage.removeItem('primaryColor')
	localStorage.removeItem('primaryHoverColor')
	localStorage.removeItem('primaryBorderColor')
	localStorage.removeItem('primaryTransparent');
	localStorage.removeItem('darkprimaryTransparent');
	localStorage.removeItem('transparentPrimary')
	localStorage.removeItem('transparentprimaryTransparent');
	document.querySelector('body').classList.add('transparent-theme');
	document.querySelector('body')?.classList.remove('light-theme');
	document.querySelector('body')?.classList.remove('dark-theme');

	$('#myonoffswitchTransparent').prop('checked', true);
    checkOptions();
	removeForTransparent();
	names()
}


function transparentBgColor() {
    $('#myonoffswitch3').prop('checked', false);
    $('#myonoffswitch6').prop('checked', false);
    $('#myonoffswitch5').prop('checked', false);
    $('#myonoffswitch8').prop('checked', false);

	let userColor = document.getElementById('transparentBgColorID').value;
	localStorage.setItem('transparentBgColor', userColor);
	localStorage.setItem('transparentThemeColor', userColor+95);
	localStorage.setItem('transparentprimaryTransparent', userColor+20);
    
    // removing light theme data 
	localStorage.removeItem('darkPrimary');
	localStorage.removeItem('darkprimaryTransparent');
	localStorage.removeItem('primaryColor')
	localStorage.removeItem('primaryHoverColor')
	localStorage.removeItem('primaryBorderColor')
	localStorage.removeItem('primaryTransparent');
    localStorage.removeItem('transparentBgImgPrimary');
	localStorage.removeItem('transparentBgImgprimaryTransparent');
	localStorage.removeItem('BgImage');
	document.querySelector('body').classList.add('transparent-theme');
	document.querySelector('body')?.classList.remove('light-theme');
	document.querySelector('body')?.classList.remove('dark-theme');
	document.querySelector('body')?.classList.remove('bg-img1');
	document.querySelector('body')?.classList.remove('bg-img2');
	document.querySelector('body')?.classList.remove('bg-img3');
	document.querySelector('body')?.classList.remove('bg-img4');

	$('#myonoffswitchTransparent').prop('checked', true);
    checkOptions();
	removeForTransparent();
}

function bgImage(e) {

    $('#myonoffswitch3').prop('checked', false);
    $('#myonoffswitch6').prop('checked', false);
    $('#myonoffswitch5').prop('checked', false);
    $('#myonoffswitch8').prop('checked', false);
	let imgID = e.getAttribute('class');
	localStorage.setItem('BgImage', imgID);
    
    // removing light theme data 
	localStorage.removeItem('darkPrimary');
	localStorage.removeItem('darkprimaryTransparent');
	localStorage.removeItem('primaryColor')
	localStorage.removeItem('transparentBgColor');
	localStorage.removeItem('transparentThemeColor');
	localStorage.removeItem('transparentprimaryTransparent');
	document.querySelector('body').classList.add('transparent-theme');
	document.querySelector('body')?.classList.remove('light-theme');
	document.querySelector('body')?.classList.remove('dark-theme');

	$('#myonoffswitchTransparent').prop('checked', true);
    checkOptions();
	removeForTransparent();
}


let myVarVal, myVarVal1
function names(){
	
	//get variable
	myVarVal  =  localStorage.getItem("primaryColor") || localStorage.getItem("darkPrimary") || localStorage.getItem("transparentPrimary") || localStorage.getItem("transparentBgImgPrimary") || "#38cab3";
	myVarVal1 =  hexToRgba(localStorage.getItem("primaryColor"),0.8) || hexToRgba(localStorage.getItem("darkPrimary"),0.8) || hexToRgba(localStorage.getItem("transparentPrimary"),0.8) || hexToRgba(localStorage.getItem("transparentBgImgPrimary"),0.8) || null;
	
	// For index.html
	if(document.querySelector('#statistics1') !== null){
        statistics1();
    }

	if(document.querySelector('#Viewers') !== null){
		viewers();
	}
	if(document.querySelector('.chart-circle') !== null){
		chartCircle();
	}

	// For index1.html
	if(document.querySelector('#statistics2') !== null){
        statistics2();
    }
	if(document.querySelector('#budget') !== null){
		budget();
	}
	if(document.querySelector('#Viewers1') !== null){
		viewers1();
	}

	// For index2.html
	if(document.querySelector('#statistics3') !== null){
        statistics3();
    }
	if(document.querySelector('#Viewers2') !== null){
		viewers2();
	}


	let colorData1 = hexToRgba(myVarVal || "#38cab3", 0.2)
	document.querySelector('html').style.setProperty('--primary02', colorData1);

	let colorData2 = hexToRgba(myVarVal || "#38cab3", 0.5)
	document.querySelector('html').style.setProperty('--primary05', colorData2);
	
}
names()

