/**
 * Movies Filter Web Component
 * Fetches filtered HTML from server
 */
export default class MoviesFilter extends HTMLElement {
  constructor() {
    super();
    this.buttons = null;
    this.moviesList = null;
  }

  connectedCallback() {
    this.buttons = this.querySelectorAll('.filter-btn');
    this.moviesList = document.querySelector('movies-list');

    this.attachEventListeners();
  }

  attachEventListeners() {
    this.buttons.forEach(btn => {
      btn.addEventListener('click', () => this.handleFilterClick(btn));
    });
  }

  async handleFilterClick(btn) {
    const genre = btn.dataset.genre;

    this.updateActiveButton(btn);
    this.updateUrl(genre);
    await this.fetchFilteredMovies(genre);
  }

  updateActiveButton(activeBtn) {
    this.buttons.forEach(btn => btn.classList.remove('active'));
    activeBtn.classList.add('active');
  }

  updateUrl(genre) {
    const url = new URL(window.location);
    if (genre === 'all') {
      url.searchParams.delete('genre');
    } else {
      url.searchParams.set('genre', genre);
    }
    window.history.pushState({}, '', url);
  }

  async fetchFilteredMovies(genre) {
    const url = genre === 'all'
      ? '/movies/'
      : `/movies/?genre=${encodeURIComponent(genre)}`;

    try {
      this.moviesList.classList.add('loading');

      const response = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (!response.ok) {
        throw new Error('Failed to fetch movies');
      }

      const html = await response.text();
      this.moviesList.innerHTML = html;
    } catch (error) {
      console.error('Error fetching movies:', error);
      this.moviesList.innerHTML = '<p>Error loading movies.</p>';
    } finally {
      this.moviesList.classList.remove('loading');
    }
  }
}

customElements.define('movies-filter', MoviesFilter);
