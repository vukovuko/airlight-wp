/**
 * Movies Filter Web Component
 * Fetches filtered HTML from server with pagination
 */
export default class MoviesFilter extends HTMLElement {
  constructor() {
    super();
    this.buttons = null;
    this.moviesList = null;
    this.currentGenre = 'all';
    this.currentPage = 1;
  }

  connectedCallback() {
    this.buttons = this.querySelectorAll('.filter-btn');
    this.moviesList = document.querySelector('movies-list');

    // Get initial state from URL
    const params = new URLSearchParams(window.location.search);
    this.currentGenre = params.get('genre') || 'all';
    this.currentPage = parseInt(params.get('paged')) || 1;

    this.attachEventListeners();
  }

  attachEventListeners() {
    // Filter buttons
    this.buttons.forEach(btn => {
      btn.addEventListener('click', () => this.handleFilterClick(btn));
    });

    // Pagination buttons (delegated since they're dynamic)
    this.moviesList.addEventListener('click', (e) => {
      const pageBtn = e.target.closest('.page-btn');
      if (pageBtn) {
        this.handlePageClick(pageBtn);
      }
    });
  }

  handleFilterClick(btn) {
    const genre = btn.dataset.genre;
    this.currentGenre = genre;
    this.currentPage = 1; // Reset to page 1 when filtering

    this.updateActiveButton(btn);
    this.fetchMovies();
  }

  handlePageClick(btn) {
    const page = parseInt(btn.dataset.page);
    this.currentPage = page;

    this.fetchMovies();
  }

  updateActiveButton(activeBtn) {
    this.buttons.forEach(btn => btn.classList.remove('active'));
    activeBtn.classList.add('active');
  }

  updateUrl() {
    const params = new URLSearchParams();

    if (this.currentGenre !== 'all') {
      params.set('genre', this.currentGenre);
    }

    if (this.currentPage > 1) {
      params.set('paged', this.currentPage);
    }

    const queryString = params.toString();
    const url = '/movies/' + (queryString ? '?' + queryString : '');

    window.history.pushState({}, '', url);
  }

  buildFetchUrl() {
    const params = new URLSearchParams();

    if (this.currentGenre !== 'all') {
      params.set('genre', this.currentGenre);
    }

    if (this.currentPage > 1) {
      params.set('paged', this.currentPage);
    }

    const queryString = params.toString();
    return '/movies/' + (queryString ? '?' + queryString : '');
  }

  showSkeletons() {
    const skeletonCount = 3;
    const skeletons = Array(skeletonCount).fill(`
      <article class="movie-item skeleton">
        <div class="skeleton-title"></div>
        <div class="skeleton-text"></div>
      </article>
    `).join('');

    this.moviesList.innerHTML = `<div class="movies-grid">${skeletons}</div>`;
  }

  showError(message) {
    this.moviesList.innerHTML = `
      <div class="movies-error">
        <p>${this.escapeHtml(message)}</p>
        <button class="retry-btn">Try Again</button>
      </div>
    `;

    this.moviesList.querySelector('.retry-btn')?.addEventListener('click', () => {
      this.fetchMovies();
    });
  }

  escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  async fetchMovies() {
    const url = this.buildFetchUrl();

    this.showSkeletons();
    this.updateUrl();

    try {
      const response = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (!response.ok) {
        throw new Error('Failed to load movies');
      }

      const html = await response.text();
      this.moviesList.innerHTML = html;

      // Scroll to top of page
      document.querySelector('.site-main')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } catch (error) {
      console.error('Error fetching movies:', error);
      this.showError('Failed to load movies. Please try again.');
    }
  }
}

customElements.define('movies-filter', MoviesFilter);
