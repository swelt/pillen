/**
 * Pillen Database Frontend Application
 */

(function($) {
    'use strict';

    const PillenApp = {
        pills: [],
        filteredPills: [],
        colors: [],
        selectedColor: null,
        searchTerm: '',

        init: function() {
            this.loadPills();
            this.setupEventListeners();
        },

        loadPills: function() {
            $.ajax({
                url: pillenData.apiUrl + '/pills',
                method: 'GET',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', pillenData.nonce);
                },
                success: (data) => {
                    this.pills = data;
                    this.extractColors();
                    this.filterPills();
                    this.renderColorFilters();
                    $('#pillen-loading').hide();
                    $('#pillen-navigation').show();
                },
                error: function(error) {
                    console.error('Error loading pills:', error);
                    $('#pillen-loading').html('<p style="color:red;">Fehler beim Laden der Daten.</p>');
                }
            });
        },

        extractColors: function() {
            const colorSet = new Set();
            this.pills.forEach(pill => {
                if (pill.colorz && Array.isArray(pill.colorz)) {
                    pill.colorz.forEach(color => colorSet.add(color));
                }
            });
            this.colors = Array.from(colorSet);
        },

        setupEventListeners: function() {
            // Color filter clicks
            $(document).on('click', '.color', (e) => {
                const $color = $(e.currentTarget);
                const color = $color.data('color');

                $('.color').removeClass('active');
                $color.addClass('active');

                if (color === 'all') {
                    this.selectedColor = null;
                } else {
                    this.selectedColor = color;
                }

                this.filterPills();
            });

            // Search input
            $('#pillen-search').on('input', (e) => {
                this.searchTerm = $(e.target).val().toLowerCase();
                this.filterPills();
            });

            // Modal close
            $('.pillen-modal-close, .pillen-modal').on('click', (e) => {
                if (e.target === e.currentTarget) {
                    $('#pillen-modal').fadeOut();
                }
            });

            // Pill click
            $(document).on('click', '.pill-item', (e) => {
                const pillId = $(e.currentTarget).data('pill-id');
                const pill = this.pills.find(p => p.id === pillId);
                if (pill) {
                    this.showPillDetails(pill);
                }
            });
        },

        filterPills: function() {
            this.filteredPills = this.pills.filter(pill => {
                // Color filter
                if (this.selectedColor) {
                    if (!pill.colorz || !pill.colorz.includes(this.selectedColor)) {
                        return false;
                    }
                }

                // Search filter
                if (this.searchTerm) {
                    const searchLower = this.searchTerm;
                    const matchName = pill.name && pill.name.toLowerCase().includes(searchLower);
                    const matchLogo = pill.logo && pill.logo.toLowerCase().includes(searchLower);
                    const matchOrt = pill.ort && pill.ort.toLowerCase().includes(searchLower);

                    if (!matchName && !matchLogo && !matchOrt) {
                        return false;
                    }
                }

                return true;
            });

            this.renderPills();
        },

        renderColorFilters: function() {
            const $container = $('#color-filters');
            $container.empty();

            this.colors.forEach((color, index) => {
                const $colorDiv = $('<div>')
                    .addClass('color')
                    .css('backgroundColor', color)
                    .attr('data-color', color)
                    .attr('id', 'color_' + index);
                $container.append($colorDiv);
            });
        },

        renderPills: function() {
            const $grid = $('#pillen-grid');
            $grid.empty();

            if (this.filteredPills.length === 0) {
                $grid.html('<p>' + 'Keine Pillen gefunden.' + '</p>');
                return;
            }

            this.filteredPills.forEach((pill, index) => {
                const $pill = $('<div>')
                    .addClass('pill-item')
                    .attr('data-pill-id', pill.id)
                    .attr('title', pill.name)
                    .attr('id', 'pill_' + index);

                // Set background color to dominant color
                if (pill.colorz && pill.colorz.length > 0) {
                    $pill.css('backgroundColor', pill.colorz[0]);
                }

                // If has image, use as background
                if (pill.images && pill.images.length > 0) {
                    $pill.css({
                        'backgroundImage': 'url(' + pill.images[0].thumbnail + ')',
                        'backgroundSize': 'cover',
                        'backgroundPosition': 'center'
                    });
                }

                $grid.append($pill);
            });
        },

        showPillDetails: function(pill) {
            const images = pill.images || [];
            let imagesHtml = '';

            if (images.length > 0) {
                imagesHtml = '<div class="pill-images">';
                images.forEach(img => {
                    imagesHtml += `<img src="${img.medium || img.url}" alt="${pill.name}">`;
                });
                imagesHtml += '</div>';
            }

            const compositionHtml = this.formatComposition(pill.composition);

            const html = `
                <h2>${pill.name}</h2>
                <div class="pill-details-content">
                    <div class="pill-details-info">
                        <table>
                            <tbody>
                                <tr>
                                    <td><strong>${pillenData.i18n.composition}</strong></td>
                                    <td>${compositionHtml || pill.inhalt || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>${pillenData.i18n.breakline}</strong></td>
                                    <td>${pill.bruchrille || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>${pillenData.i18n.date}</strong></td>
                                    <td>${this.formatDate(pill.datum) || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>${pillenData.i18n.thickness}</strong></td>
                                    <td>${pill.dicke || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>${pillenData.i18n.diameter}</strong></td>
                                    <td>${pill.durchmesser || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>${pillenData.i18n.place}</strong></td>
                                    <td>${pill.ort || '-'}</td>
                                </tr>
                                ${pill.gewicht ? `<tr><td><strong>Gewicht</strong></td><td>${pill.gewicht}</td></tr>` : ''}
                                ${pill.logo ? `<tr><td><strong>Logo</strong></td><td>${pill.logo}</td></tr>` : ''}
                                ${pill.quelle ? `<tr><td><strong>Quelle</strong></td><td>${pill.quelle}</td></tr>` : ''}
                            </tbody>
                        </table>
                    </div>
                    <div class="pill-details-images">
                        ${imagesHtml}
                    </div>
                </div>
            `;

            $('#pillen-modal-body').html(html);
            $('#pillen-modal').fadeIn();
        },

        formatComposition: function(composition) {
            if (!composition || typeof composition !== 'object') {
                return '';
            }

            const items = Object.entries(composition).map(([substance, amount]) => {
                return `${substance}: ${amount}`;
            });

            return items.join('<br>');
        },

        formatDate: function(dateStr) {
            if (!dateStr) return '';

            // Try to parse YYYY-MM-DD format
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                return `${parts[2]}.${parts[1]}.${parts[0]}`;
            }

            return dateStr;
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        if ($('#pillen-app').length > 0) {
            PillenApp.init();
        }
    });

})(jQuery);
