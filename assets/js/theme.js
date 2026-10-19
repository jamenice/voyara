/**
 * Fallback header: opens and closes the primary menu on small screens.
 */
( function () {
	const btn = document.querySelector( '.vy-menu-toggle' );
	const nav = document.querySelector( '.vy-nav' );
	if ( ! btn || ! nav ) {
		return;
	}
	const set = ( open ) => {
		btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		nav.classList.toggle( 'is-open', open );
	};
	btn.addEventListener( 'click', () => set( btn.getAttribute( 'aria-expanded' ) !== 'true' ) );
	document.addEventListener( 'keydown', ( e ) => {
		if ( e.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
			set( false );
			btn.focus();
		}
	} );
} )();

/**
 * Reading progress bar on posts (Theme Options > Blog > Reading progress bar).
 */
( function () {
	const bar = document.querySelector( '.vy-progress__bar' );
	const entry = document.querySelector( '.vy-entry__content' );
	if ( ! bar || ! entry ) {
		return;
	}
	let ticking = false;
	const update = () => {
		const rect = entry.getBoundingClientRect();
		const total = rect.height - window.innerHeight;
		const done = total > 0 ? Math.min( 1, Math.max( 0, -rect.top / total ) ) : 1;
		bar.style.transform = 'scaleX(' + done + ')';
		ticking = false;
	};
	const queue = () => {
		if ( ! ticking ) {
			ticking = true;
			window.requestAnimationFrame( update );
		}
	};
	window.addEventListener( 'scroll', queue, { passive: true } );
	window.addEventListener( 'resize', queue );
	update();
} )();

/**
 * My account login: the Sign in and Create account tabs.
 */
( function () {
	const tabs = [ ...document.querySelectorAll( '.vy-login__tabs [role="tab"]' ) ];
	if ( ! tabs.length ) {
		return;
	}
	const show = ( tab, focus ) => {
		tabs.forEach( ( t ) => {
			const on = t === tab;
			t.setAttribute( 'aria-selected', on ? 'true' : 'false' );
			t.tabIndex = on ? 0 : -1;
			document.getElementById( t.getAttribute( 'aria-controls' ) ).hidden = ! on;
		} );
		if ( focus ) {
			tab.focus();
		}
	};
	// WooCommerce shows the password strength on keyup and change only. A pasted or
	// autofilled password showed it on blur, which pushed the Create account button down
	// as it was clicked, so the click missed it. Check the strength on every input.
	const pass = document.getElementById( 'reg_password' );
	if ( pass && window.jQuery ) {
		pass.addEventListener( 'input', () => window.jQuery( pass ).trigger( 'keyup' ) );
	}
	tabs.forEach( ( tab, i ) => {
		tab.addEventListener( 'click', () => show( tab, false ) );
		tab.addEventListener( 'keydown', ( e ) => {
			if ( e.key === 'ArrowRight' || e.key === 'ArrowLeft' ) {
				e.preventDefault();
				show( tabs[ ( i + ( e.key === 'ArrowRight' ? 1 : tabs.length - 1 ) ) % tabs.length ], true );
			}
		} );
	} );
} )();

/*
 * Checkout order summary: Remove takes the line out without leaving the page. The link
 * is WooCommerce's own remove URL (with the cart nonce), so it still works without
 * JavaScript; here it is called in the background, then WooCommerce redraws the order
 * table and totals, and the header cart count follows. With the last line gone the
 * checkout reloads and WooCommerce shows the empty cart.
 */
( function () {
	document.addEventListener( 'click', ( e ) => {
		const link = e.target.closest( '#order_review .vy-oitem__remove' );
		const $ = window.jQuery;
		if ( ! link || ! $ || ! window.fetch ) {
			return;
		}
		e.preventDefault();
		const row = link.closest( 'tr' );
		if ( row ) {
			row.classList.add( 'is-removing' );
			row.setAttribute( 'aria-busy', 'true' );
		}
		window
			.fetch( link.href, { credentials: 'same-origin', redirect: 'manual' } )
			.then( () => {
				$( document.body ).trigger( 'update_checkout' );
				$( document.body ).trigger( 'wc_fragment_refresh' );
			} )
			.catch( () => {
				window.location.href = link.href;
			} );
	} );
} )();

/*
 * Checkout errors: WooCommerce scrolls to its notices leaving 100px above them, which
 * the sticky header and the admin bar cover. Scroll again with room for both.
 */
( function () {
	const $ = window.jQuery;
	if ( ! $ ) {
		return;
	}
	$( document.body ).on( 'checkout_error', () => {
		const notice = document.querySelector( 'form.checkout > .woocommerce-NoticeGroup' );
		if ( ! notice ) {
			return;
		}
		const root = window.getComputedStyle( document.documentElement );
		const room = ( parseFloat( root.getPropertyValue( '--vy-head-h' ) ) || 0 ) + ( parseFloat( root.getPropertyValue( '--wp-admin--admin-bar--height' ) ) || 0 ) + 16;
		const reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		$( 'html, body' ).stop( true );
		window.scrollTo( { top: Math.max( 0, notice.getBoundingClientRect().top + window.scrollY - room ), behavior: reduce ? 'auto' : 'smooth' } );
	} );
} )();

/*
 * Order summary on the cart and checkout pages: a sticky column taller than the
 * window scrolls with the page until its end (the Place order button) is in view,
 * then stays there; scrolling up brings its top back the same way.
 */
( function () {
	const el = document.querySelector( '.vy-wcpage form.checkout #order_review, .vy-wcpage--cart .cart_totals' );
	if ( ! el ) {
		return;
	}
	const gap = 16;
	const offset = () => parseFloat( window.getComputedStyle( document.documentElement ).getPropertyValue( '--vy-head-offset' ) ) || 0;
	let base = 0;
	let top = null;
	let last = window.scrollY;
	let raf = 0;
	const update = () => {
		raf = 0;
		const y = window.scrollY;
		const dy = y - last;
		last = y;
		if ( 'sticky' !== window.getComputedStyle( el ).position ) {
			el.style.removeProperty( 'top' );
			el.style.removeProperty( 'transition' );
			top = null;
			return;
		}
		const head = offset();
		const min = window.innerHeight - el.offsetHeight - gap;
		if ( min >= base + head ) {
			if ( null !== top ) {
				el.style.removeProperty( 'top' );
				el.style.removeProperty( 'transition' );
				top = null;
			}
			return;
		}
		top = Math.min( base + head, Math.max( min, ( null === top ? base + head : top ) - dy ) );
		// The top follows each scroll step here, so the CSS transition would make it lag.
		el.style.transition = 'none';
		el.style.top = top + 'px';
	};
	const ask = () => {
		if ( ! raf ) {
			raf = window.requestAnimationFrame( update );
		}
	};
	const measure = () => {
		el.style.removeProperty( 'top' );
		el.style.removeProperty( 'transition' );
		// The CSS top includes the sticky header offset; keep the base without it.
		base = ( parseFloat( window.getComputedStyle( el ).top ) || 0 ) - offset();
		top = null;
		update();
	};
	window.addEventListener( 'scroll', ask, { passive: true } );
	window.addEventListener( 'vy:head', ask );
	window.addEventListener( 'resize', measure );
	if ( window.ResizeObserver ) {
		new window.ResizeObserver( ask ).observe( el );
	}
	measure();
} )();
