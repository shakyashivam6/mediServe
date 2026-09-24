(() => {
    const enhance = (select) => {
        if (!(select instanceof HTMLSelectElement) || select.dataset.searchEnhanced === 'true' || select.dataset.optionSearch === 'false') return;
        select.dataset.searchEnhanced = 'true';

        const input = document.createElement('input');
        input.type = 'search';
        input.autocomplete = 'off';
        input.spellcheck = false;
        input.className = 'option-search-input form-control form-control-sm';
        input.placeholder = 'Type to filter options';
        const label = [...select.labels].map(item => item.textContent.trim()).join(' ') || select.name || 'dropdown';
        input.setAttribute('aria-label', `Filter ${label} options`);
        input.style.maxWidth = select.style.maxWidth || '100%';
        input.disabled = select.disabled;
        select.parentNode.insertBefore(input, select);

        const filterOptions = () => {
            const query = input.value.trim().toLocaleLowerCase();
            [...select.options].forEach(option => {
                if (!Object.prototype.hasOwnProperty.call(option, 'optionSearchWasHidden')) {
                    option.optionSearchWasHidden = option.hidden;
                }
                const matches = !query || option.value === '' || option.textContent.toLocaleLowerCase().includes(query);
                option.hidden = option.optionSearchWasHidden || !matches;
            });
        };

        input.addEventListener('input', filterOptions);
        select.addEventListener('change', () => {
            if (!input.value) return;
            input.value = '';
            filterOptions();
        });
        new MutationObserver(filterOptions).observe(select, { childList: true, subtree: true });
        new MutationObserver(() => { input.disabled = select.disabled; })
            .observe(select, { attributes: true, attributeFilter: ['disabled'] });
        filterOptions();
    };

    const scan = node => {
        if (node instanceof HTMLSelectElement) enhance(node);
        node.querySelectorAll?.('select').forEach(enhance);
    };

    document.querySelectorAll('select').forEach(enhance);
    new MutationObserver(records => records.forEach(record => record.addedNodes.forEach(scan)))
        .observe(document.documentElement, { childList: true, subtree: true });
})();
