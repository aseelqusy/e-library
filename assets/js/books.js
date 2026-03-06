// Book interactions and favorites functionality

document.addEventListener('DOMContentLoaded', function() {

    // ═══════════════════════════════════════════════════════════════════
    //                      FAVORITE TOGGLE
    // ═══════════════════════════════════════════════════════════════════

    document.addEventListener('click', function(e) {
        const favoriteBtn = e.target.closest('.book-favorite-btn');
        if (favoriteBtn) {
            e.preventDefault();
            e.stopPropagation();
            toggleFavorite(favoriteBtn);
        }
    });

    function toggleFavorite(btn) {
        const bookId = btn.dataset.bookId;
        const isFavorited = btn.classList.contains('favorited');

        // Send AJAX request
        fetch('/library_project/api/toggle-favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ book_id: bookId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (isFavorited) {
                    btn.classList.remove('favorited');
                    btn.innerHTML = '🤍';
                } else {
                    btn.classList.add('favorited');
                    btn.innerHTML = '❤️';
                }
            } else {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Failed to update favorite');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    // ═══════════════════════════════════════════════════════════════════
    //                      FILTER CHIPS
    // ═══════════════════════════════════════════════════════════════════

    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            // Toggle active state
            this.classList.toggle('active');

            // Apply filters
            applyFilters();
        });
    });

    // Clear filters button
    const clearFiltersBtn = document.querySelector('.clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            document.querySelectorAll('.filter-chip').forEach(chip => {
                chip.classList.remove('active');
            });
            applyFilters();
        });
    }

    function applyFilters() {
        const categories = [];
        const statuses = [];

        document.querySelectorAll('.filter-chip.active').forEach(chip => {
            const filterType = chip.dataset.filterType;
            const filterValue = chip.dataset.filterValue;

            if (filterType === 'category') {
                categories.push(filterValue);
            } else if (filterType === 'status') {
                statuses.push(filterValue);
            }
        });

        // Build URL with filters
        const url = new URL(window.location);

        if (categories.length > 0) {
            url.searchParams.set('categories', categories.join(','));
        } else {
            url.searchParams.delete('categories');
        }

        if (statuses.length > 0) {
            url.searchParams.set('status', statuses.join(','));
        } else {
            url.searchParams.delete('status');
        }

        // Reload page with new filters
        window.location.href = url.toString();
    }

    // ═══════════════════════════════════════════════════════════════════
    //                      SEARCH FUNCTIONALITY
    // ═══════════════════════════════════════════════════════════════════

    const searchForm = document.querySelector('.search-bar-large form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            if (!searchInput.value.trim()) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }

    // ═══════════════════════════════════════════════════════════════════
    //                      SMOOTH ANIMATIONS
    // ═══════════════════════════════════════════════════════════════════

    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe book cards
    document.querySelectorAll('.book-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });

});

// ═══════════════════════════════════════════════════════════════════
//                      BORROW BOOK
// ═══════════════════════════════════════════════════════════════════

function borrowBook(bookId, buttonElement) {
    if (!confirm('Do you want to borrow this book?')) {
        return;
    }

    fetch('/library_project/api/borrow-book.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ book_id: bookId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Book borrowed successfully!');

            // Update button
            if (buttonElement) {
                buttonElement.innerHTML = '<i class="fas fa-check"></i> Borrowed';
                buttonElement.disabled = true;
                buttonElement.style.opacity = '0.6';
            }

            // Optionally reload page
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'Failed to borrow book');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

// ═══════════════════════════════════════════════════════════════════
//                      RETURN BOOK
// ═══════════════════════════════════════════════════════════════════

function returnBook(borrowingId, buttonElement) {
    if (!confirm('Mark this book as returned?')) {
        return;
    }

    fetch('/library_project/api/return-book.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ borrowing_id: borrowingId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Book returned successfully!');

            // Update UI
            if (buttonElement) {
                const borrowedItem = buttonElement.closest('.borrowed-item');
                if (borrowedItem) {
                    borrowedItem.style.opacity = '0';
                    setTimeout(() => {
                        borrowedItem.remove();
                    }, 300);
                }
            }
        } else {
            alert(data.message || 'Failed to return book');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

// ═══════════════════════════════════════════════════════════════════
//                      SEARCH AUTO-SUGGEST
// ═══════════════════════════════════════════════════════════════════

const searchInput = document.querySelector('.search-input-large');
if (searchInput) {
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);

        const query = this.value.trim();

        if (query.length < 2) {
            return;
        }

        debounceTimer = setTimeout(() => {
            // Could implement auto-suggest here
            console.log('Searching for:', query);
        }, 300);
    });
}

