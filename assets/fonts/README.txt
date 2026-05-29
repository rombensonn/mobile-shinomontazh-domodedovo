SF Pro Display is integrated in CSS through local() font sources and the site-wide --font-display variable.

The font itself is not bundled here because SF Pro is licensed by Apple and should only be used according to its license terms.

If the owner has licensed web font files, place them in this folder and add url(...) sources to the @font-face rules in assets/css/style.css.
Recommended filenames:
- SF-Pro-Display-Regular.woff2
- SF-Pro-Display-Medium.woff2
- SF-Pro-Display-Semibold.woff2
- SF-Pro-Display-Bold.woff2

Without local or licensed files, browsers will use the system fallback stack: -apple-system, BlinkMacSystemFont, Segoe UI, Arial, sans-serif.
