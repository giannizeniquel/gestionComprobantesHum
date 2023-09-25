const loadingBackground = document.createElement('div');
const loading = document.createElement('div');
const imgLoading = document.createElement('img');
imgLoading.src = "../../public/GCP_blanco2.gif";

loadingBackground.classList = 'loading-background';
loadingBackground.style.backgroundColor = '#fff';
loadingBackground.style.width = '100%';
loadingBackground.style.height = '100%';
loadingBackground.style.position = 'absolute';
loadingBackground.style.zIndex = '9999';

loading.classList = 'loading';
loading.style.borderRadius = '50%';
loading.style.width = '60px';
loading.style.height = '60px';
loading.style.position ='fixed';
/* Ponemos el valor de left, bottom, right y top en 0 */
loading.style.left = '0';
loading.style.bottom = '0';
loading.style.right = '0';
loading.style.top = '0';
loading.style.margin ='auto'; /* Centramos vertical y horizontalmente */

imgLoading.style.borderRadius = '50%';
imgLoading.style.width = '80px';
imgLoading.style.height = '80px';

loading.appendChild(imgLoading);
loadingBackground.appendChild(loading);
document.head.after(loadingBackground);

window.addEventListener('DOMContentLoaded', function() {
      $(".loading-background").fadeOut(1000);
});

