    </div>
  </main>

  <!-- Now Playing Bar -->
  <footer class="now-playing">
    <div class="now-playing__content">
      <div class="now-playing__track">
        <img src="https://via.placeholder.com/56" alt="Album Art" class="now-playing__artwork">
        <div class="now-playing__info">
          <div class="now-playing__title">Song Title</div>
          <div class="now-playing__artist">Artist Name</div>
        </div>
        <button class="btn--icon" aria-label="Add to favorites">
          <i class="far fa-heart"></i>
        </button>
      </div>

      <div class="now-playing__controls">
        <div class="control-buttons">
          <button class="btn--icon" aria-label="Shuffle">
            <i class="fas fa-random"></i>
          </button>
          <button class="btn--icon" aria-label="Previous track">
            <i class="fas fa-step-backward"></i>
          </button>
          <button class="btn--play" aria-label="Play/Pause">
            <i class="fas fa-play"></i>
          </button>
          <button class="btn--icon" aria-label="Next track">
            <i class="fas fa-step-forward"></i>
          </button>
          <button class="btn--icon" aria-label="Repeat">
            <i class="fas fa-redo"></i>
          </button>
        </div>

        <div class="progress-bar">
          <span class="progress-time">0:00</span>
          <div class="progress-track">
            <div class="progress-fill"></div>
          </div>
          <span class="progress-time">3:45</span>
        </div>
      </div>

      <div class="volume-controls">
        <button class="btn--icon" aria-label="Mute">
          <i class="fas fa-volume-up"></i>
        </button>
        <input 
          type="range" 
          class="volume-slider" 
          min="0" 
          max="100" 
          value="80"
          aria-label="Volume control"
        >
      </div>
    </div>
  </footer>

  <script src="/js/main.js"></script>
</body>
</html>
