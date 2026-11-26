# Admin AJAX CRUD and Realtime Search Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make CRUD updates and live searches across all six searchable admin lists work without full-page reloads while retaining server-side pagination.

**Architecture:** Keep the existing GET list queries and pagination. Render each list's table and pager through a reusable view fragment, return that fragment as JSON for AJAX GET requests, and let `admin-crud.js` replace only the list area for search, pagination, and successful CRUD refreshes.

**Tech Stack:** CodeIgniter 4, PHP views/controllers, Fetch API, existing jQuery/Bootstrap admin UI, custom PHP test runner.

**Spec:** `docs/superpowers/specs/2026-11-24-admin-ajax-realtime-search-design.md`

## Global Constraints

- Search all admin lists that currently have search fields: users, symptoms, diseases, samples, articles, and decision-tree rules.
- Use the existing server-side filters and pagination; do not load all records into the browser.
- Do not change search fields, matching rules, page size, or unrelated public/user flows.
- Preserve current validation feedback, CSRF refresh, and normal non-AJAX controller fallbacks.
- Follow the current CodeIgniter, Bootstrap/jQuery, and controller/model patterns.

## Review Focus

- Emptying a search must reset the page selector and show unfiltered first-page results. Test: `testEmptySearchResetsTheListPage` in Task 2.
- A slow response to an older query must not replace results for newer input. Test: `testOlderSearchResponsesDoNotReplaceNewerResults` in Task 2.
- Pager clicks must preserve the current keyword and update only the list. Test: `testPagerRequestsPreserveKeywordAndReplaceOnlyList` in Task 2.
- Create, update, and delete must refresh the current filtered page and pager without reloading the document. Test: `testCrudRefreshesListWithoutFullReload` in Task 2.
- A failed or non-JSON AJAX response must show an error and leave the existing list intact. Test: `testFailedListRequestsLeaveExistingRowsVisible` in Task 2.

---

### Task 1: Add server-rendered AJAX list fragments

**Files:**
- Modify: `app/Controllers/Admin.php`
- Modify: `app/Views/admin/userlist.php`
- Modify: `app/Views/admin/gejala.php`
- Modify: `app/Views/admin/penyakit.php`
- Modify: `app/Views/admin/sample.php`
- Modify: `app/Views/admin/artikel.php`
- Modify: `app/Views/admin/decisiontree.php`
- Create: `app/Views/admin/fragments/userlist.php`
- Create: `app/Views/admin/fragments/gejala.php`
- Create: `app/Views/admin/fragments/penyakit.php`
- Create: `app/Views/admin/fragments/sample.php`
- Create: `app/Views/admin/fragments/artikel.php`
- Create: `app/Views/admin/fragments/decisiontree.php`
- Test: `tests/AdminRealtimeSearchTest.php`
- Modify: `tests/AdminArticleTest.php`
- Modify: `tests/UiUsabilityTest.php`

**Interfaces:**
- Consumes: each list controller's existing filtered rows, pager, and page-size data.
- Produces: each page view has a `data-live-search` form, a `data-admin-list` wrapper, and includes its matching fragment; an AJAX GET to the same route returns `ajaxResponse(['html' => view('admin/fragments/<name>', $data)])`.
- Page selectors: `page_users`, `page_gejala`, `page_penyakit`, `page_sample`, `page`, and `page_decision_tree` respectively.

- [ ] **Step 1: Write failing list-fragment tests**

Add `testAllSearchableAdminListsExposeLiveSearchAndListFragments` to assert the six list views expose `data-live-search` and `data-admin-list`, and that each view includes its matching fragment.

Add `testAdminListEndpointsReturnFragmentsForAjax` to assert `Admin.php` has AJAX JSON fragment responses for all six list methods while retaining their normal full-view returns.

Add `testDecisionTreeCurrentPageUsesItsPagerSelector` to assert the displayed decision-tree page number reads `page_decision_tree`.

- [ ] **Step 2: Run the tests and verify they fail**

Run: `php tests/run.php`
Expected: the new realtime-search tests fail because the list fragments and AJAX responses do not exist yet.

- [ ] **Step 3: Extract each table and pager into its fragment**

Move each list's result table and pager from its full view into the matching `app/Views/admin/fragments/*.php` file. Keep search controls, page headings, and unrelated content in the original views. Add `data-admin-list` around the fragment include and mark the existing search form `data-live-search`. Update existing tests that inspect table content or empty states to inspect the matching fragment.

- [ ] **Step 4: Return list fragments for AJAX GET requests**

In `Admin.php`, after preparing each list's existing `$data`, return `ajaxResponse(['html' => view('admin/fragments/<name>', $data)])` when `isAJAX()` is true; otherwise keep returning the original full page view. Set the decision-tree displayed page from `page_decision_tree`.

- [ ] **Step 5: Run the focused tests**

Run: `php tests/run.php`
Expected: all six list-fragment tests pass, and existing tests still pass.

### Task 2: Add debounced AJAX search, pagination, and CRUD list refresh

**Files:**
- Modify: `public/js/admin-crud.js`
- Modify: `tests/AdminRealtimeSearchTest.php`
- Test: `tests/AdminAjaxTest.php`
- Test: `tests/AjaxModalTest.php`

**Interfaces:**
- Consumes: `data-live-search`, `data-admin-list`, the page selector values from Task 1, and JSON `{ status, html, csrf }` responses from list endpoints.
- Produces: delegated input and pagination handlers that request server-filtered fragments; a successful CRUD request refreshes the current list fragment. Non-list AJAX forms retain their existing redirect/reload behavior.

- [ ] **Step 1: Write failing frontend behavior tests**

Add `testEmptySearchResetsTheListPage`, `testOlderSearchResponsesDoNotReplaceNewerResults`, `testPagerRequestsPreserveKeywordAndReplaceOnlyList`, `testCrudRefreshesListWithoutFullReload`, and `testFailedListRequestsLeaveExistingRowsVisible`. Assert the corresponding input, pagination, stale-response, CRUD-refresh, and error behaviors. Keep existing modal and CSRF assertions.

- [ ] **Step 2: Run the tests and verify they fail**

Run: `php tests/run.php`
Expected: the new script behavior assertions fail against the current submit/reload implementation.

- [ ] **Step 3: Implement delegated live search and pagination**

In `public/js/admin-crud.js`, debounce search input by 300 ms, reset the page selector when the keyword changes, and use Fetch with `Accept: application/json` and `X-Requested-With: XMLHttpRequest`. Intercept pager links inside `data-admin-list`, retain the current keyword, and replace only the wrapper contents from the returned `html`. Prevent older requests from overwriting newer results.

- [ ] **Step 4: Refresh the current list after successful CRUD**

When a successful AJAX form belongs to a page with `data-admin-list`, close the modal and fetch the list's current URL before replacing its contents. Use this for create, update, and delete so pagination and active filtering remain accurate. Preserve existing behavior for AJAX forms outside list pages and preserve error/CSRF handling.

- [ ] **Step 5: Run the full test suite**

Run: `php tests/run.php`
Expected: all tests pass, including existing CRUD validation, modal, CSRF, and redirect fallback checks.
