# Maria Center Design System

## Color tokens

These Tailwind CSS v4 theme tokens are declared together in `resources/css/app.css`:

| Token | Value | Utility examples |
|---|---|---|
| `--color-background` | `#F5F2ED` | `bg-background` |
| `--color-surface` | `#FFFFFF` | `bg-surface` |
| `--color-primary` | `#A38B54` | `bg-primary`, `text-primary`, `border-primary` |
| `--color-primary-hover` | `#B49C6E` | `hover:bg-primary-hover` |
| `--color-secondary` | `#766868` | `text-secondary`, `border-secondary` |
| `--color-muted` | `#979290` | `text-muted` |

## Visual rules

- Use the warm background for page canvas and white surfaces for cards and controls.
- Prefer a thin, quiet border and generous spacing over prominent shadows.
- Mark the current navigation item with a soft `bg-primary/10` tint and `text-primary`; keep inactive links secondary and bring them to primary on hover.
- Give the main action a solid primary fill. Use outlined secondary actions for supporting tasks.
- The muted text color has a contrast ratio of about 2.75:1 against the background, below WCAG AA's 4.5:1 target for normal text. Reserve `text-muted` for small secondary labels and timestamps; use stronger text colors for body copy, important values, and controls.
- Use warm `text-amber-800` sparingly for outstanding balances that need attention; use primary for zero balances.

## Pages not yet migrated

Apply this system to these remaining CRUD screens in a later step:

- Stages and grades
- Subjects and academic periods
- Students and families
- Teachers

Enrollment, payment, payroll, and report screens also remain to be reviewed and migrated separately.
