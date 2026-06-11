---
name: Academic Integrity
colors:
  surface: '#f7faf6'
  surface-dim: '#d8dbd7'
  surface-bright: '#f7faf6'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f1f4f0'
  surface-container: '#ecefeb'
  surface-container-high: '#e6e9e5'
  surface-container-highest: '#e0e3df'
  on-surface: '#181c1a'
  on-surface-variant: '#3f4944'
  inverse-surface: '#2d312f'
  inverse-on-surface: '#eef2ed'
  outline: '#6f7973'
  outline-variant: '#bec9c2'
  surface-tint: '#1b6b51'
  primary: '#004532'
  on-primary: '#ffffff'
  primary-container: '#065f46'
  on-primary-container: '#8bd6b7'
  inverse-primary: '#8bd6b6'
  secondary: '#565e74'
  on-secondary: '#ffffff'
  secondary-container: '#dae2fd'
  on-secondary-container: '#5c647a'
  tertiary: '#652925'
  on-tertiary: '#ffffff'
  tertiary-container: '#823f3a'
  on-tertiary-container: '#ffb4ad'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#a6f2d1'
  primary-fixed-dim: '#8bd6b6'
  on-primary-fixed: '#002116'
  on-primary-fixed-variant: '#00513b'
  secondary-fixed: '#dae2fd'
  secondary-fixed-dim: '#bec6e0'
  on-secondary-fixed: '#131b2e'
  on-secondary-fixed-variant: '#3f465c'
  tertiary-fixed: '#ffdad6'
  tertiary-fixed-dim: '#ffb3ac'
  on-tertiary-fixed: '#3b0908'
  on-tertiary-fixed-variant: '#73332f'
  background: '#f7faf6'
  on-background: '#181c1a'
  surface-variant: '#e0e3df'
  status-active: '#10b981'
  status-inactive: '#94a3b8'
  status-pending: '#f59e0b'
  status-error: '#ef4444'
  surface-muted: '#f8fafc'
  border-subtle: '#e2e8f0'
typography:
  display-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 60px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.02em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  container-margin-mobile: 16px
  container-margin-desktop: 40px
  gutter: 24px
  touch-target: 44px
---

## Brand & Style

This design system is built upon the principles of **Institutional Authority** and **Functional Transparency**. Designed for an academic environment, the aesthetic is professional, modern, and highly legible, ensuring that complex administrative data feels accessible and trustworthy.

The chosen style is **Corporate / Modern** with a strong emphasis on **Minimalism**. By leveraging generous whitespace, a strictly governed grid, and a high-contrast palette centered around the institutional green, the interface conveys a sense of stability and academic rigor. The UI serves as a silent, reliable framework that elevates the professional profiles of the university's staff and faculty. Visual noise is eliminated to prioritize information hierarchy and ease of use on mobile devices, particularly for QR-code-driven public interactions.

## Colors

The color strategy is anchored by "Hijau Institusi" (Emerald Green), which represents the heritage and integrity of the university. This primary color is used strategically for brand identifiers, primary actions, and key navigation elements. 

To maintain a clean, institutional feel, the background uses a crisp white or a very light gray (`surface-muted`) to differentiate content sections. Secondary actions and high-level typography utilize a deep Slate (`#0f172a`) to ensure maximum readability and professional contrast. Semantic colors for badges and status indicators are slightly desaturated to remain harmonious with the brand green while providing clear visual cues for administrative workflows.

## Typography

The typography system uses a pairing of **Plus Jakarta Sans** for headings and **Inter** for body text. Plus Jakarta Sans provides a friendly yet sophisticated modern touch to the institutional headers, while Inter offers world-class legibility for dense data and administrative forms.

The scale is mobile-first; headings scale down gracefully for phone screens while maintaining significant weight to preserve hierarchy. All body text is optimized for long-form reading of academic bios and technical details, with a strict adherence to a 1.5x line-height ratio for optimal vertical rhythm.

## Layout & Spacing

The layout utilizes a **Fluid Grid** system that transitions from a 4-column layout on mobile to a 12-column layout on desktop. 

A 8px base unit (the "spacing rhythm") governs all margins and paddings. Content cards are separated by 24px gutters to allow the UI to breathe. On mobile devices, the side margins are tightened to 16px to maximize screen real estate for profile data, while desktop layouts expand to 40px or more to maintain an elegant, centered composition. 

All interactive elements (buttons, inputs, links) must adhere to a minimum touch target of 44px in height to ensure accessibility for users viewing profiles on mobile via QR code scans.

## Elevation & Depth

This system uses **Tonal Layers** and **Low-Contrast Outlines** rather than heavy shadows. Depth is communicated through subtle shifts in background color.

- **Level 0 (Floor):** Page background using `surface-muted`.
- **Level 1 (Card):** White background with a 1px `border-subtle` outline. This is the primary container for profile information and table data.
- **Level 2 (Interaction):** Subtle, soft ambient shadows (8% opacity, 12px blur) are used exclusively for floating elements like dropdown menus or active modals to separate them from the main content plane.

This approach keeps the interface feeling "flat" and lightweight, which aligns with modern web standards and high-speed mobile performance.

## Shapes

The design system uses a **Rounded** shape language (8px / 0.5rem) to soften the institutional nature of the app and make it feel more approachable. 

- **Standard Elements:** Buttons, Input Fields, and Cards use the 8px radius.
- **Large Elements:** Profile header sections or large image containers use a 16px radius (rounded-lg).
- **Status Badges:** Use a full "Pill" radius to distinguish them as non-interactive status indicators.
- **Avatars:** Use circular masks to create a friendly, personal connection to the faculty members.

## Components

### Buttons
Primary buttons use the Emerald Green background with white text. Secondary buttons use a white background with a `border-subtle` and Slate text. Hover states should involve a slight darkening of the background color (e.g., Green-900).

### Badges (Active/Inactive)
Badges must be pill-shaped. **Active** badges use a light green background with dark green text. **Inactive** badges use a light gray background with Slate-500 text. Use these for visibility status and employment state.

### Data Tables
Tables should be clean and borderless where possible, using horizontal dividers (`border-subtle`) only. The header row should have a subtle background (`surface-muted`) and use `label-md` typography. Rows should have a subtle hover highlight.

### Input Fields
Inputs use a white background, a 1px gray border, and 8px rounded corners. The focus state must use a 2px Emerald Green ring to provide clear visual feedback.

### QR Code Cards
The QR code is a primary asset. It should be housed in a Level 1 card, centered, with a clear "Download SVG/PNG" action group below it.

### Form Elements
Checkboxes and radios should utilize the primary Emerald Green for their "checked" state. Group labels should use the `label-md` type level for clarity.