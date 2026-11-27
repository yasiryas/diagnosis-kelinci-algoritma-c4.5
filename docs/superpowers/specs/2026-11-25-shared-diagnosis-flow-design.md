# Shared Diagnosis Flow

## Intent

Make guest and signed-in diagnosis consistent and easier to complete. When a rule matches, show the result first; signed-in users choose whether to save it. If validation or matching fails, keep their selected symptoms checked.

## Goals

- Reuse the existing guest diagnosis form and result flow for signed-in users.
- Ensure no diagnosis is saved automatically; only the explicit authenticated save action writes history.
- Preserve selected symptoms on empty-selection, no-match, and missing-disease outcomes.
- Refresh the diagnosis form and result presentation with clear hierarchy, grouped large symptom choices, progress/context cues, accessible feedback, and responsive layout.
- Retain the current decision-tree matcher, diagnosis rules, and non-diagnosis dashboard UI.

## Approaches

- **One shared flow (chosen):** use the existing `/diagnosa` form/result screens for guests and members, and keep the authenticated POST save endpoint for the explicit history action. This reuses the guest flow's selection retention and prevents the member flow from auto-saving.
- **Separate guest/member flows:** fix and redesign both independently. Rejected because the existing duplicate controller logic has already caused inconsistent save and error behavior.

## Design and constraints

Build on the existing CodeIgniter views, Bootstrap components, Nunito typography, public layout, and CSS. Use a calm, trustworthy teal/green direction with strong text contrast rather than the search result's high-contrast-risk neumorphic treatment. Keep controls keyboard-operable with visible focus, clear labels and errors, touch-friendly sizing, and responsive layouts. Preserve the existing guidance that results are decision support, not a substitute for a veterinarian.

Keep `/user/diagnosis`, `/user/prosesdiagnosis`, and `/user/konsultasi` compatible while routing members through the shared experience. Do not write a result until the signed-in user submits the explicit save action.

## Validation

Add focused tests for member entry to the shared flow, result-before-save behavior, selected-symptom retention on failures, and accessible/rendered diagnosis UI. Run the existing PHP suite and validate the responsive flow in a browser where the app runtime permits.
