/**
 * Movies Filter Web Component
 * Enhances PHP-rendered movies with client-side filtering
 */
export default class MoviesFilter extends HTMLElement {
  constructor() {
    super();
    this.buttons = null;
    this.moviesList = null;
    this.movies = null;
    this.activeGenre = 'all';
  }

  connectedCallback() {
    this.buttons = this.querySelectorAll('.filter-btn');
    this.moviesList = document.querySelector('movies-list');
    this.movies = this.moviesList?.querySelectorAll('.movie-item');

    this.attachEventListeners();
  }

  attachEventListeners() {
    this.buttons.forEach(btn => {
      btn.addEventListener('click', () => this.handleFilterClick(btn));
    });
  }

  handleFilterClick(btn) {
    const genre = btn.dataset.genre;
    this.activeGenre = genre;

    this.updateActiveButton(btn);
    this.filterMovies(genre);
  }

  updateActiveButton(activeBtn) {
    this.buttons.forEach(btn => btn.classList.remove('active'));
    activeBtn.classList.add('active');
  }

  filterMovies(genre) {
    if (!this.movies) return;

    this.movies.forEach(movie => {
      const movieGenres = movie.dataset.genres?.split(',') || [];

      if (genre === 'all' || movieGenres.includes(genre)) {
        movie.style.display = '';
      } else {
        movie.style.display = 'none';
      }
    });
  }
}

customElements.define('movies-filter', MoviesFilter);
