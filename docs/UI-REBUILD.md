# UI Rebuild & Navigation Fix

## What changed

- Rebuilt the home page with a premium dark performance-lab visual system.
- Added responsive mobile navigation.
- Added a demo dashboard hero that labels benchmark values as pending until Lighthouse runs are recorded.
- Added engineering feature cards and audit-method section.
- Rebuilt the before/after report page with mobile and desktop comparison cards.
- Added clear CTA hierarchy and responsive touch targets.
- Reduced reliance on client-side navigation for the Home/Back button.

## Navigation bug fix

The previous report page used an Inertia `Link` for the Home control. The new report page uses a normal browser anchor:

```html
<a href="/" class="back-link">...</a>
```

This is intentionally resilient: if the JavaScript bundle has a loading/runtime issue, the browser can still navigate back to `/`.

## Visual direction

- Dark technical hero
- Cyan + indigo performance accents
- Glass dashboard card
- High-contrast typography
- Soft grid background
- Responsive mobile layout
- Lightweight CSS visuals instead of heavy image libraries

## Performance considerations

The redesign avoids large JavaScript animation libraries and keeps the dashboard CSS-driven. The current home page does not render the hero SVG or any raster images; its unused preload was removed. On narrow screens, decorative blurred orbs are hidden and the sticky header blur is disabled to reduce visual effects on mobile GPUs.
