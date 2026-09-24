<script>
(() => {
    const feedback = document.querySelector('.cart-feedback');
    let feedbackTimer;

    const notify = (message, isError = false) => {
        if (!feedback) return;
        feedback.textContent = message;
        feedback.style.background = isError ? '#9f3545' : '#073d31';
        feedback.classList.add('visible');
        clearTimeout(feedbackTimer);
        feedbackTimer = setTimeout(() => feedback.classList.remove('visible'), 2400);
    };

    const applyCartState = (state) => {
        document.querySelectorAll('#cart-count').forEach(el => el.textContent = state.cartCount);
        if (state.subtotal !== undefined) {
            document.querySelectorAll('[data-cart-subtotal]').forEach(el => el.textContent = `₹${Number(state.subtotal).toFixed(2)}`);
        }
        if (state.productId && state.quantity !== undefined) {
            document.querySelectorAll(`[data-product-id="${state.productId}"]`).forEach(card => {
                const controls = card.querySelector('.product-quantity');
                if (controls) {
                    controls.style.display = 'flex';
                    controls.querySelector('[data-quantity]').textContent = state.quantity;
                }
                const addButton = card.querySelector('.cart-add-form button');
                if (addButton) addButton.textContent = 'Add another';
            });
            document.querySelectorAll(`[data-cart-row="${state.productId}"]`).forEach(row => {
                const quantity = row.querySelector('[data-quantity]');
                if (quantity) quantity.textContent = state.quantity;
                const total = row.querySelector('[data-line-total]');
                if (total && state.lineTotal !== null) total.textContent = `₹${Number(state.lineTotal).toFixed(2)}`;
            });
        }
    };

    const send = async (url, options) => {
        const response = await fetch(url, {
            ...options,
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', ...(options.headers || {}) },
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Could not update your cart.');
        applyCartState(data);
        notify(data.message || 'Cart updated.');
    };

    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('.cart-add-form');
        if (!form) return;
        event.preventDefault();
        const button = form.querySelector('button');
        button.disabled = true;
        try {
            await send(form.action, { method: 'POST', body: new FormData(form) });
        } catch (error) {
            notify(error.message, true);
        } finally {
            button.disabled = false;
        }
    });

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-qty-step]');
        if (!button) return;
        const controls = button.closest('.qty-control');
        const current = Number(controls.querySelector('[data-quantity]').textContent);
        const quantity = Math.max(1, Math.min(99, current + Number(button.dataset.qtyStep)));
        if (quantity === current) return;
        controls.querySelectorAll('button').forEach(el => el.disabled = true);
        try {
            await send(controls.dataset.quantityUrl, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': controls.querySelector('[name="_token"]').value },
                body: JSON.stringify({ quantity }),
            });
        } catch (error) {
            notify(error.message, true);
        } finally {
            controls.querySelectorAll('button').forEach(el => el.disabled = false);
        }
    });
})();
</script>
