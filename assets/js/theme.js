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
