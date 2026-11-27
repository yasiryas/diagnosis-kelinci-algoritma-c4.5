# Shared Diagnosis Flow Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Give guests and signed-in users one accessible diagnosis flow that retains symptom choices, shows results before saving, and makes history saving explicit.

**Architecture:** Keep `Diagnosis::index/proses` as the canonical form/result flow. Redirect the member GET entry points to it and route the legacy member POST consultation endpoint to the same processor. Keep `User::simpanDiagnosis` as the only member history write. Refresh the shared diagnosis form and result views with scoped responsive styling.

**Tech Stack:** CodeIgniter 4, PHP controllers/views, existing Bootstrap and Nunito styles, custom PHP test runner with `ViewRenderer` and `TestDom`.

**Spec:** `docs/superpowers/specs/2026-11-25-shared-diagnosis-flow-design.md`

## Global Constraints

- Reuse the guest diagnosis flow for members and preserve selected symptoms on empty-selection, no-match, and missing-disease outcomes.
- Do not save diagnosis history automatically; saving happens only through the authenticated explicit POST action.
- Retain the current decision-tree matcher, diagnosis rules, and non-diagnosis dashboard UI.
- Build on CodeIgniter views, Bootstrap components, Nunito typography, public layout, and scoped app CSS.
- Maintain keyboard-operable labeled controls, visible focus, strong text contrast, touch-friendly sizing, responsive layout, and the veterinarian-advice disclaimer.

## Review Focus

- A member following either existing GET entry point must reach the shared diagnosis form. Test: `testMemberDiagnosisEntrypointsRedirectToSharedFlow` in Task 1.
- The legacy member POST route must use the shared processor and must not auto-save. Test: `testMemberKonsultasiRouteUsesSharedNonSavingProcessor` in Task 1.
- Members must still be able to save history only with the explicit authenticated POST action. Test: `testMemberHistorySaveRemainsAnExplicitPost` in Task 1.
- A selected symptom must remain checked when errors are shown. Test: `testSelectedSymptomsRemainCheckedAfterValidationFailure` in Task 2.
- Guest and member result pages must offer the correct distinct next action, without implying a diagnosis is certain. Tests: `testMemberResultRendersExplicitSaveAction` and `testGuestResultRendersLoginAction` in Task 2.

---

### Task 1: Route member diagnosis through the shared processor

**Files:**
- Modify: `app/Config/Routes.php`
- Modify: `app/Controllers/User.php`
- Test: `tests/MemberDiagnosisFlowTest.php`

**Interfaces:**
- Consumes: `Diagnosis::index()`, `Diagnosis::proses()`, and the protected `user/diagnosis` POST save endpoint.
- Produces: member GET routes `user/diagnosis` and `user/prosesdiagnosis` redirect to `diagnosa`; legacy POST `user/konsultasi` dispatches to `Diagnosis::proses`; `User::simpanDiagnosis()` remains the only member persistence path.

- [ ] **Step 1: Write failing route and save-flow tests**

Add `testMemberDiagnosisEntrypointsRedirectToSharedFlow`, `testMemberKonsultasiRouteUsesSharedNonSavingProcessor`, and `testMemberHistorySaveRemainsAnExplicitPost` to `tests/MemberDiagnosisFlowTest.php`. Assert both GET methods redirect to `site_url('diagnosa')`, the protected POST route targets `Diagnosis::proses`, and the save route remains POST to `User::simpanDiagnosis`.

- [ ] **Step 2: Run the tests and verify they fail**

Run: `php tests/run.php`
Expected: the new member flow tests fail against the current member-specific GET views and auto-saving consultation method.

- [ ] **Step 3: Route member entry points to the shared flow**

Change `User::diagnosis()` and `User::prosesDiagnosis()` to redirect to `site_url('diagnosa')`. Change the protected `user/konsultasi` POST route to `Diagnosis::proses`, and remove the now-unused member consultation method that saves results automatically. Keep `User::simpanDiagnosis()` unchanged as the explicit authenticated save handler.

- [ ] **Step 4: Run the focused route tests**

Run: `php tests/run.php`
Expected: the three new member-flow tests and existing guest diagnosis tests pass.

### Task 2: Redesign the shared diagnosis form and result

**Files:**
- Modify: `app/Views/diagnosis/index.php`
- Modify: `app/Views/diagnosis/hasil.php`
- Modify: `public/css/app.css`
- Modify: `tests/Support/ViewRenderer.php`
- Test: `tests/DiagnosisUiTest.php`

**Interfaces:**
- Consumes: `title`, grouped `gejala`, `errors`, and `selected` from the form controller; `hasil`, `gejalaDetail`, and authenticated `user()` state from the result controller.
- Produces: an accessible grouped symptom-card form with an error summary and preserved checked states; a result page that shows the matched symptoms and disease guidance, then offers guests login and members an explicit CSRF-protected save form.

- [ ] **Step 1: Write failing rendered-view tests**

Add `testDiagnosisFormRendersGroupedSelectableSymptoms`, `testSelectedSymptomsRemainCheckedAfterValidationFailure`, `testMemberResultRendersExplicitSaveAction`, and `testGuestResultRendersLoginAction` in `tests/DiagnosisUiTest.php`. Render the views with `ViewRenderer` fixtures and inspect actual DOM output with `TestDom`.

- [ ] **Step 2: Run the tests and verify they fail**

Run: `php tests/run.php`
Expected: the new rendered-view tests fail because the current pages lack the grouped flow and redesigned next-action presentation.

- [ ] **Step 3: Redesign the form view**

In `diagnosis/index.php`, keep the existing POST action and CSRF field. Group symptoms by category into labeled, touch-friendly selectable cards; show the current step and what happens next; retain the error summary and mark IDs from `$selected` as checked.

- [ ] **Step 4: Redesign the result view and scope styles**

In `diagnosis/hasil.php`, show the step context, selected/matched symptoms, possible disease, solution, and medicine in a readable hierarchy. Keep a login action for guests and the explicit CSRF-protected `user/diagnosis` POST save for members. Add scoped `diagnosis-*` styles in `app.css` using a calm teal/green palette, responsive cards, visible focus, and sufficient text contrast; do not alter global dashboard styles.

- [ ] **Step 5: Run the full test suite and lint changed PHP**

Run: `php tests/run.php`
Expected: all tests pass, including guest, member, rendered-view, and existing flow-safety tests.

Run: `php -l app/Controllers/User.php; php -l app/Config/Routes.php; php -l app/Views/diagnosis/index.php; php -l app/Views/diagnosis/hasil.php`
Expected: no PHP syntax errors.
