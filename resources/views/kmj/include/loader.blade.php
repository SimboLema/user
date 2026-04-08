      <!-- Circular Loader Overlay -->
      <div class="circleLoaderOverlay1"
          style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background: rgba(0,0,0,0.7); z-index:9999; justify-content:center; align-items:center;
     flex-direction:column; color:#fff; font-family:sans-serif;">

          <!-- Close Button -->
          <button onclick="closeLoader(true)"
              style="position:absolute; top:20px; right:20px; background:#9aa89b; border:none; color:#fff;
        font-size:16px; padding:5px 10px; border-radius:5px; cursor:pointer;">X</button>

          <!-- SPIN LOADER -->
          <div class="spinLoader"
              style="width:80px; height:80px; border:8px solid #444; border-top:8px solid #9aa89b;
         border-radius:50%; animation: spin 1s linear infinite;">
          </div>

          <div style="margin-top:20px; font-size:16px;">Processing...</div>
      </div>

      <!-- PROGRESS LOADER -->
      <div class="circleLoaderOverlay2"
          style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background: rgba(0,0,0,0.7); z-index:9999; justify-content:center; align-items:center;
     flex-direction:column; color:#fff; font-family:sans-serif;">
          <button onclick="closeLoader(true)"
              style="position:absolute; top:20px; right:20px; background:#9aa89b; border:none; color:#fff;
        font-size:16px; padding:5px 10px; border-radius:5px; cursor:pointer;">X</button>

          <div style="position:relative; width:120px; height:120px;">
              <svg viewBox="0 0 36 36" style="width:100%; height:100%;">
                  <path d="M18 2.0845
                                 a 15.9155 15.9155 0 0 1 0 31.831
                                 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#333" stroke-width="2" />
                  <path class="circleProgress" d="M18 2.0845
                                 a 15.9155 15.9155 0 0 1 0 31.831
                                 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#9aa89b" stroke-width="3"
                      stroke-dasharray="0,100" stroke-linecap="round" />
              </svg>
              <div class="circlePercent"
                  style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);
             font-size:18px; font-weight:bold;">
                  0%</div>
          </div>
          <div style="margin-top:20px; font-size:16px;">Waiting for TIRA...</div>
      </div>

      <style>
          @keyframes spin {
              0% {
                  transform: rotate(0deg);
              }

              100% {
                  transform: rotate(360deg);
              }
          }
      </style>


      @if (session('tira_success'))
          <script>
              window.addEventListener('load', function() {
                  startSecondLoader();
              });
          </script>
      @endif



      <script>
          let loaderActive = false;

          function showLoaderWithClose(el) {

              // First ask user confimation
              const userConfirmed = confirm("Are you sure you want to proceed?");
              if (!userConfirmed) {
                  return false; // User cancelled
              }

              // Disable link to prevent multiple clicks
              el.style.pointerEvents = 'none';
              el.style.opacity = '0.6';
              el.disabled = true;



              if (loaderActive) return false;
              loaderActive = true;

              const overlay = document.querySelector('.circleLoaderOverlay1');
              const spinner = document.querySelector('.spinLoader');

              overlay.style.display = 'flex';
              spinner.style.display = 'block';

              // no percent/progress in this loader
              return true;
          }

          // PROGRESS LOADER (30 sec)
          function startSecondLoader() {

              // Disable TIRA button permanently
              const tiraBtn = document.querySelector('.tiraButton');
              if (tiraBtn) {
                  tiraBtn.style.pointerEvents = 'none';
                  tiraBtn.style.opacity = '0.6';
                  tiraBtn.disabled = true;
              }

              const overlay = document.querySelector('.circleLoaderOverlay2');
              const progress = document.querySelector('.circleProgress');
              const percentText = document.querySelector('.circlePercent');

              overlay.style.display = 'flex';
              progress.style.display = 'block';
              percentText.style.display = 'block';

              let duration = 30 * 1000;
              let start = null;

              function animate(timestamp) {
                  if (!start) start = timestamp;

                  let elapsed = timestamp - start;
                  let percent = Math.min((elapsed / duration) * 100, 100);

                  percentText.textContent = Math.floor(percent) + '%';
                  progress.setAttribute('stroke-dasharray', percent + ',100');

                  if (elapsed < duration) {
                      requestAnimationFrame(animate);
                  } else {
                      overlay.style.display = 'none';
                      location.reload();
                  }
              }

              requestAnimationFrame(animate);
          }

          // CLOSE BUTTON
          function closeLoader(refreshPage = false) {
              const spinnerOverlay = document.querySelector('.circleLoaderOverlay1');
              const progressOverlay = document.querySelector('.circleLoaderOverlay2');

              if (spinnerOverlay) spinnerOverlay.style.display = 'none';
              if (progressOverlay) progressOverlay.style.display = 'none';

            //   if (refreshPage) location.reload();
          }
      </script>
