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
                    controls.style.display = Number(state.quantity) > 0 ? 'flex' : 'none';
                    controls.querySelector('[data-quantity]').textContent = state.quantity;
                    const decreaseButton = controls.querySelector('[data-qty-step="-1"], [data-qty-remove]');
                    if (decreaseButton && Number(state.quantity) > 0) {
                        if (Number(state.quantity) === 1) {
                            decreaseButton.removeAttribute('data-qty-step');
                            decreaseButton.setAttribute('data-qty-remove', '');
                            decreaseButton.setAttribute('aria-label', 'Remove from cart');
                            decreaseButton.title = 'Remove from cart';
                            decreaseButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m19 6-1 14H6L5 6"/><path d="M10 11v5M14 11v5"/></svg>';
                        } else {
                            decreaseButton.removeAttribute('data-qty-remove');
                            decreaseButton.removeAttribute('title');
                            decreaseButton.setAttribute('data-qty-step', '-1');
                            decreaseButton.setAttribute('aria-label', 'Decrease quantity');
                            decreaseButton.textContent = '−';
                        }
                    }
                }
                const addButton = card.querySelector('.cart-add-form button');
                if (addButton) addButton.textContent = Number(state.quantity) > 0 ? 'Add another to cart' : 'Add to cart';
            });
            document.querySelectorAll(`[data-cart-row="${state.productId}"]`).forEach(row => {
                if (Number(state.quantity) < 1) {
                    row.remove();
                    if (!document.querySelector('[data-cart-row]')) location.reload();
                    return;
                }
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
        const removeButton = event.target.closest('[data-qty-remove]');
        if (removeButton) {
            const controls = removeButton.closest('.qty-control');
            controls.querySelectorAll('button').forEach(el => el.disabled = true);
            try {
                await send(controls.dataset.removeUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': controls.querySelector('[name="_token"]').value },
                });
            } catch (error) {
                notify(error.message, true);
            } finally {
                controls.querySelectorAll('button').forEach(el => el.disabled = false);
            }
            return;
        }

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
