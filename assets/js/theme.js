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
