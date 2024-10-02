// // Font Loader Script
// (function() {
//     const fonts = [
//         {
//             family: 'Antaresia',
//             urls: ['../assets/fonts/Antaresia.woff', '../assets/fonts/Antaresia.woff2'],
//             weight: 'normal',
//             style: 'normal',
//         },

//     ];

//     fonts.forEach(font => {
//         console.log(font)
//         alert(1)
//         const fontFace = new FontFace(font.family, `url(${font.urls.join('"), url(')})`, {
//             weight: font.weight,
//             style: font.style,
//             display: 'swap',
//         });

//         document.fonts.add(fontFace);
//         fontFace.load().then(() => {
//             document.body.style.fontFamily = font.family;
//             console.log(`Font ${font.family} loaded successfully.`);
//         }).catch(error => {
//             console.error(`Failed to load font ${font.family}:`, error);
//         });
//     });
// })();
// alert();
WebFontConfig = {
    custom: {
        families: [
            "JosefinSans-Regular",
            "Botanica Script",
            "AbrilFatface-Regular",
            "AdleryPro-Regular",
            "AgencyFB-Bold",
            "AlexBrush-Regular",
            "Allura-Regular",
            "Antaresia",
            "BotanicaScript-Regular",
            "ArcherBold",
            "Archer-Book",
            "Archer-BookItalic",
            "Archer-ExtraLight",
            "Archer-Hairline",
            "Bebas-Regular",
            "BookAntiqua",
            "Bungee-Regular",
            "CandyCaneUnregistered",
            "CarbonBl-Regular",
            "CarmenSans-ExtraBold",
            "CarmenSans-Regular",
            "ChristmasCookies",
        ],
        urls: [
            "https://yesvite.cmexpertiseinfotech.in/assets/event/css/stylesheet.css",
        ],
    },
    loading: function () {
        console.log("loading");
    },
    active: function () {
        console.log("active");
    },
    inactive: function () {
        console.log("inactive");
    },
    fontloading: function (fontFamily, fontDescription) {
        console.log(
            "fontloading: " + fontFamily + " (" + fontDescription + ")"
        );
    },
    fontactive: function (fontFamily, fontDescription) {
        console.log("fontactive: " + fontFamily + " (" + fontDescription + ")");
    },
    fontinactive: function (fontFamily, fontDescription) {
        console.log(
            "fontinactive: " + fontFamily + " (" + fontDescription + ")"
        );
    },
};
WebFont.load(WebFontConfig);
