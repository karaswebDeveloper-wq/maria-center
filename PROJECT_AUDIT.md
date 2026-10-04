# Maria Center Project Audit

## 1. Project Overview

The repository contains a Laravel 12 / PHP 8.2 application skeleton with Livewire 4 and Alpine.js. The implemented domain code covers academic stages and grades, families and students, teachers and subjects, academic periods, enrollments, payments, teacher payroll, a dashboard, and several reports/exports. CRUD controllers and Blade screens exist for most catalog entities. Livewire screens are present for dashboard, student/enrollment listings, payments, payroll, and reports. The checked-in routes do not define login; although they refer to auth/login classes, those classes are absent. The code is therefore an incomplete implementation, and no end-to-end behavior was verified during this read-only audit.

## 2. What's Implemented

Legend: ✅ present on disk; ❌ absent; ⚠️ partial or mismatched. “View” refers to an expected feature screen, not necessarily every view returned by its class. Tests are marked present only where a related test file exists; coverage is not proof the code passes.

| Feature/entity | Migration | Model | Service | Controller / Livewire | View | Tests |
|---|---:|---:|---:|---:|---:|---:|
| Users / administrator | ✅ | ✅ `User` | ❌ | ⚠️ auth routes reference missing classes | ❌ login view | ✅ example/auth-adjacent only; no login coverage found |
| Stages | ✅ | ❌ `Stage` missing | ❌ | ✅ `StageController` | ✅ | ❌ |
| Grades | ✅ | ✅ `Grade` | ❌ | ✅ `GradeController` | ⚠️ files are under `resources/views/Grades/`; controller requests `grades.*` | ❌ |
| Families | ✅ | ✅ `Family` | ❌ | ✅ `FamilyController` | ✅ | ❌ |
| Students | ✅ | ✅ `Student` | ❌ | ⚠️ controller plus `StudentsTable` | ✅ | ⚠️ indirectly used by other domain tests; no student CRUD test |
| Subjects | ✅ | ✅ `Subject` | ❌ | ✅ `SubjectController` | ⚠️ files are under `resources/views/Subject/`; controller requests `subjects.*` | ❌ |
| Teachers | ✅ | ✅ `Teacher` | ❌ | ✅ `TeacherController` | ✅ | ⚠️ used by report/payroll tests; no teacher CRUD test |
| Academic periods | ✅ | ✅ `AcademicPeriod` | ❌ | ✅ `AcademicPeriodController` | ✅ | ⚠️ indirect use; no period CRUD test |
| Enrollments | ✅ | ✅ `Enrollment` | ⚠️ `EnrollmentService` source has nonmatching filename | ⚠️ Livewire classes are referenced under different names; see Missing or Broken | ⚠️ several paths mismatch | ✅ `EnromentPaymentTest.php` (enrollment/payment flow) |
| Payments | ✅ | ❌ `Payment` model missing | ⚠️ `PaymentService` source has nonmatching filename | ⚠️ Livewire payment class referenced under a different name | ⚠️ payment view path mismatch | ✅ `paymentServiceTest.php`, `EnromentPaymentTest.php` |
| Teacher payroll | ✅ | ✅ `TeacherPayroll`, `TeacherPayrollItem` | ⚠️ `PayrollService` source has nonmatching filename | ⚠️ Livewire class names/file casing mismatch | ✅ payroll list/detail views exist | ✅ `PallServiceTest.php`, `PayrollManagementTest.php` |
| Dashboard | — | — | ✅ `DashboardService` | ✅ `Dashboard` | ✅ | ✅ `DashboardTest.php`, `DashboardServiceTest.php` |
| Teacher report | — | — | ✅ `TeacherReportService` | ⚠️ class namespace/file mismatch | ✅ under `reports/` (not Livewire expected path) | ✅ `TeacherReportServiceTest.php` |
| Teacher payroll report | — | — | ✅ `TeacherPayrollReportService` | ⚠️ class namespace/file mismatch | ✅ under `reports/` (not Livewire expected path) | ✅ `TeacherPayrollReportServiceTest.php` |
| Monthly statistics | — | — | ✅ `MonthlyStatisticsService` | ⚠️ class namespace/file mismatch | ✅ under `reports/` (not Livewire expected path) | ✅ `MonthlyStatisticsServiceTest.php` |
| General statistics | — | — | ✅ `GeneralStatisticsService` | ⚠️ class namespace/file mismatch | ✅ under `reports/` (not Livewire expected path) | ✅ `GenerelStatisticsServiceTest.php` |
| Financial report | — | — | ✅ `FinancialReportService` | ❌ no controller/Livewire route found | ❌ no matching view found | ❌ |

## 3. Missing or Broken

### Views expected by routes/controllers/components

The following expected paths are missing exactly as referenced (Laravel view notation shown with its `.blade.php` path):

- `Route::view('/', 'home')`: `resources/views/home.blade.php` is missing. This route is also immediately replaced by the next `GET /` route to `Dashboard` and duplicates the `home` route name.
- `StudentController@index`: `resources/views/students/index.blade.php` exists and mounts `<livewire:students-table />`.
- `StudentController` view targets `students.create`, `students.edit`: both files exist.
- `GradeController`: expects `resources/views/grades/index.blade.php`, `grades/create.blade.php`, `grades/edit.blade.php`. On disk these are capitalized `resources/views/Grades/...`; this may work on Windows but fails on case-sensitive deployments. The shared form expected by those templates is also named `_from.blade.php` (typo) on disk.
- `SubjectController`: expects `resources/views/subjects/{index,create,edit}.blade.php`; files are under `resources/views/Subject/` with uppercase singular directory. This is case-sensitive and pluralization mismatch. Shared partial is `_from.blade.php`.
- `EnrollmentForm::render()`: `resources/views/livewire/enrollment-form.blade.php` is missing; disk file is misspelled `resources/views/livewire/erollment-fom.blade.php`.
- `EnrollmentPayments::render()`: expects `resources/views/livewire/enrollment-payments.blade.php`; disk file is `resources/views/livewire/enrollments-payments.blade.php` (extra “s”).
- `TeacherReport::render()`, `TeacherPayrollReport::render()`, `MonthlyStatistics::render()`, `GeneralStatistics::render()` expect respectively `resources/views/livewire/reports/teacher-report.blade.php`, `teacher-payroll-report.blade.php`, `monthly-statistics.blade.php`, and `general-statistics.blade.php`. None exist in that directory. Existing files are under `resources/views/reports/` and are not the same paths.
- `ReportExportController` expects `resources/views/pdf/general-statistics.blade.php`; on disk the filename is misspelled `resources/views/pdf/genernal-statistics.blade.php`.
- `User` / login flow has no corresponding `resources/views/livewire/auth/login.blade.php` or other login view; no login component source exists either.

Existing view files (all on disk): `resources/views/academic-periods/{_from,create,edit,index}.blade.php`; `resources/views/components/{confirm-delete-from.blade.php,layouts/app.blade.php}`; `resources/views/families/{_form,create,edit,index}.blade.php`; `resources/views/Grades/{_from,create,edit,index}.blade.php`; `resources/views/livewire/{dashboard,enrollments-payments,enrollments-table,erollment-fom,payroll-show,payrolls-index,students-table}.blade.php`; `resources/views/pdf/{_styles,genernal-statistics,monthly-statistics,payroll-report,teacher-report}.blade.php`; `resources/views/reports/{general-statistics,index,monthly-statistics,teacher-payroll-report,teacher-report}.blade.php`; `resources/views/stages/{_form,create,edit,index}.blade.php`; `resources/views/students/{_form,create,edit,index}.blade.php`; `resources/views/Subject/{_from,create,edit,index}.blade.php`; `resources/views/teachers/{_from,create,edit,index}.blade.php`.

### Class, namespace, and autoload mismatches

- `routes/web.php` imports `App\Livewire\EnrollmentForm`, `EnrollmentPayments`, `PayrollsIndex`, and `PayrollShow`, but actual classes are declared in `EnrollmentFrom.php`, `EnrommentPayments.php`, `payrollsIndex.php`, and `payrollShow.php`. On case-sensitive PSR-4 filesystems, Composer cannot reliably autoload these class names from the files present.
- The four report component classes declare namespace `App\Livewire\Reports` but live directly in `app/Livewire/Reports.php`, `TeacherPayrollReport.php`, `MonthlyStatistics.php`, and `GeneralStatistics.php`. Route imports expect `App\Livewire\Reports\...`, which PSR-4 maps to `app/Livewire/Reports/...`; that directory/files do not exist. `Reports.php` declares class `TeacherReport` rather than `Reports`.
- Services declare singular/PascalCase class names in incorrectly named files: `EnrollmentService` in `app/Services/EnrollmentServices.php`, `PaymentService` in `paymentService.php`, `PayrollService` in `payrollServices.php`, `MonthlyStatisticsService` in `MonthlyStatisticsServices.php`, and `TeacherPayrollReportService` in `TeacherpayrollReportService.php`. These violate Composer's PSR-4 class-to-file naming, particularly on case-sensitive systems.
- `StudentController` imports `App\Http\Requests\UpdateStudentRequest`, but the only file/class found is `UpdateStaudentRequest.php` / `UpdateStaudentRequest` (misspelled). Student update will fail to resolve its Form Request.
- `routes/web.php` imports `App\Http\Controllers\Auth\LogoutController` and `App\Livewire\Auth\Login`; no `app/Http/Controllers/Auth/LogoutController.php`, `app/Livewire/Auth/Login.php`, or equivalent classes exist. The login class is imported but no login route is declared; the logout route targets the missing controller.
- `Grade::stage()` references `App\Models\Stage`; no `app/Models/Stage.php` exists. Grade relationship loading, stage controller CRUD, stage seeders, student form data, and `StageFactory` use therefore fail at runtime.`r`n- `Enrollment::payments()` and `PaymentService` reference `App\Models\Payment`; no `app/Models/Payment.php` exists. Payment creation and relationship use therefore fail at runtime.
- `Enrollment::factory()` is used in tests and by other factories, but no `database/factories/EnrollmentFactory.php` exists. There is no `AcademicPeriodFactory` or `FamilyFactory` either, despite those model factories being invoked in tests/other factories. `paymentFactory.php` has lowercase class filename; it is not the conventional `PaymentFactory.php` mapping.

### Model and migration cross-check

All table-creating migrations reference tables created by earlier migrations in the listed lexical execution order: users, stages, grades, families, students, subjects, teachers, academic periods, enrollments, payments, teacher payrolls, teacher payroll items. No foreign key points to a table absent from the migration set, and there is no out-of-order dependency.

Model-side issues found:

- The `Payment` model is absent despite `payments` table and relations/services/tests.
- `User` has no domain relationship for payments received or payrolls created, though corresponding foreign keys exist; this is an absent convenience relationship, not a broken declared relationship.
- Declared relationships use real migration columns, including `period_id`, `payroll_id`, and `created_by`, but two target model classes are absent: `Grade::stage()` targets missing `Stage`, and `Enrollment::payments()` targets missing `Payment`. Other declared model relationships point to existing model classes and matching foreign-key columns. Every `$fillable`/cast field inspected maps to a migration column, except for the missing `Payment` model.
- `StoreStudentRequest` / misspelled update request validate an upload as `image`, while the database/model field is `image_path`; the controller maps it to `image_path`, so this is an intentional input-to-storage mapping rather than an absent column.

### Routes and runtime risks

- `GET /` is declared twice and both routes are named `home`; `Route::view('/', 'home')` is shadowed by `Route::get('/', Dashboard::class)` and its target view is missing.
- All catalog resource routes use valid existing controller classes. Each controller's resource actions and Form Request pairs exist except the student update request mismatch above. Controller-returned view directories for Grade and Subject have capitalization/pluralization mismatches described above.
- The export routes target existing `ReportExportController` methods: `reports.teacher.pdf`, `reports.teacher.excel`, `reports.payroll.pdf`, `reports.payroll.excel`, `reports.monthly.pdf`, `reports.monthly.excel`, `reports.general.pdf`, and `reports.general.excel`. General PDF expects the misspelled view path listed above.
- Feature routes for dashboard/reports/payroll/enrollments are not grouped behind `auth`; only `/` and logout are in the auth group. This exposes management/report screens without authentication if the app is reachable.
- `Route::get('/reports/teacher', ...)` and the other report routes refer to class names that don't autoload from the current paths/namespaces.
- Migration `2026_09_23_185656_create_teachers_table.php` creates `percentage` as non-nullable with no default, while `StoreTeacherRequest` must be checked against all form submissions; an omitted percentage would cause insert failure. Migration columns otherwise align with entity request fields on inspection.

### Configuration and setup state

- `.env.example` sets `APP_LOCALE=en`, `APP_FALLBACK_LOCALE=en`, and `APP_FAKER_LOCALE=en_US`; Arabic locale is not the default. `config/maria.php` supplies default admin name/email/password and reads `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`; `.env.example` does not declare these variables.
- `.env` exists and contains entries for `APP_LOCALE`, DB keys, and all three `ADMIN_*` keys. Secret values were not included in this report. Its configured locale/DB values were not printed or recorded.
- No `public/storage` symlink was found. Image URLs use the `public` disk, so uploaded student/teacher images will not be publicly reachable through the conventional URL until `php artisan storage:link` is run.
- `composer.json` requires PHP `^8.2`, Laravel `^12.0`, Livewire `^4.4`, DomPDF and Excel packages; PHP 8.2.12, Composer, `composer.lock`, and `vendor/autoload.php` are present. `composer install` is plausible but was not run; actual platform requirements and scripts remain unverified.
- `package.json` declares Vite 7, Tailwind 4, `@tailwindcss/vite`, and Alpine 3. `package-lock.json`, `node_modules`, and Node/npm are present. `npm install` is plausible but was not run.
- `php artisan migrate` was not run because it changes database state. Migration files have valid dependency order. Whether it succeeds depends on the active `.env` database driver, credentials, and PHP extensions; this audit did not connect to or modify a database. `SESSION_DRIVER=database` is the example setting and the sessions table exists in the users migration.
- Neither `.env.example` nor the root listing includes a `tailwind.config.*`; Tailwind 4 is configured through Vite, so the brand palette could later be added in CSS via Tailwind v4 theme variables (or an explicit config if the project adopts one).
- No test suite was run (the request was a read-only audit). Existing test files cannot be assumed to pass; missing model factories, model classes, service autoload mappings, and report service tests importing `App\Services\Reports\...` from mismatched source paths are likely blockers.

## 4. Inconsistencies

- Repeated spelling/case errors include `EnrollmentFrom`, `EnrommentPayments`, `erollment-fom`, `EnromentPaymentTest`, `PallServiceTest`, `GenerelStatisticsServiceTest`, `genernal-statistics`, and `_from` used where `_form` is expected.
- View directory names vary in singular/plural and case (`Subject`, `Grades`, `subjects`, `grades`), which can conceal failures on Windows and break on Linux.
- Livewire report components and services are organized under namespaces that do not match their file paths. Several service filenames use pluralized or lowercase variants of their class names.
- The migration set includes standard Laravel users/cache/jobs tables as expected. The domain migrations have sensible foreign-key order and mostly matching columns; that part is structurally coherent.
- `FinancialReportService` exists but no route, controller, Livewire component, or view uses it.
- `.env.example` is the stock English Laravel template, while seeders and user-facing validation messages contain Arabic strings. `resources/views/components/layouts/app.blade.php` should be checked for Arabic direction/font/locale behavior during the UI phase.

## 5. Setup Checklist

From a clean clone, the intended local setup is:

1. `composer install` — PHP 8.2+ is required. `composer.lock` is present; package resolution appears plausible but has not been executed here.
2. `Copy-Item .env.example .env` (PowerShell) or `cp .env.example .env` (Unix), then set `APP_LOCALE=ar`, the selected `DB_*` connection values, and `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`. `.env.example` currently defaults to English and omits `ADMIN_*`, although `config/maria.php` has fallback defaults.
3. `php artisan key:generate` — `.env.example` has an empty `APP_KEY`.
4. Create/configure the database named by `DB_DATABASE` and confirm the matching PDO extension/credentials are available. The current committed example uses SQLite, but the actual local `.env` is user-specific and was not recorded.
5. `php artisan migrate` — migration ordering and referenced tables are coherent. This command has not been run; database connectivity/driver is unverified.
6. `php artisan db:seed` — runs administrator, stages, grades, subjects, academic periods. Admin credentials come from `config/maria.php`; change the fallback password before using the application outside local development.
7. `npm install` — package lock and `node_modules` exist in this checkout; clean-install success is unverified.
8. `npm run build` — Vite build script exists; no build was run.
9. `php artisan storage:link` — required for public access to stored images; `public/storage` is currently absent.
10. `php artisan serve` and browse the app. End-to-end startup is not verified and is blocked by missing/mismatched auth, Livewire, service, model, request, and view references listed above.

## 6. UI / Design System (for later, do not implement yet)

| Token | Color |
|---|---|
| Background | `#F5F2ED` |
| Cards / Surface | `#FFFFFF` |
| Primary | `#A38B54` |
| Primary Hover | `#B49C6E` |
| Secondary | `#766868` |
| Muted Text | `#979290` |

Tailwind 4 is present through `@tailwindcss/vite`, and no `tailwind.config.*` was found. The palette should likely be declared as theme color variables in the main Tailwind CSS entry point using Tailwind v4's `@theme` convention, then consumed consistently by Blade components. No CSS/Tailwind file was changed in this audit.

## 7. Recommended Next Steps

1. Repair autoload/class/request naming and supply the missing `Payment` model and required factories so application classes and existing tests can load.
2. Resolve duplicate and unauthenticated routes and implement the missing login/logout targets.
3. Align all route/controller/Livewire/PDF view references with actual Blade paths, including the Grade/Subject casing and report component paths.
4. Run dependency/platform checks, migrations, seeders, and the existing tests; fix any concrete failures revealed.
5. Build and verify the missing management and report views, then apply the recorded Arabic RTL design system consistently.

## Audit Inventory

### Models and enums

Model files found: `AcademicPeriod`, `Enrollment`, `Family`, `Grade`, `Student`, `Subject`, `Teacher`, `TeacherPayroll`, `TeacherPayrollItem`, and `User`. The `Stage` and `Payment` models are missing despite corresponding tables. Declared fillable/cast fields map to migration columns; `Enrollment::payments()` is unresolved because `Payment` is missing, and `Grade::stage()` is unresolved because `Stage` is missing. Other declared relationships point to matching migration FK columns: `family_id`, `grade_id`, `student_id`, `teacher_id`, `subject_id`, `period_id`, `enrollment_id`, `payroll_id`, and `created_by`.

Enums: `DurationType` (`full_month`, `half_month`) is cast by `Enrollment`; `EnrollmentStatus` (`active`, `cancelled`) is cast by `Enrollment` and used by enrollment queries/services; `PaymentMethod` (`cash`, `bank_transfer`, `mobile_wallet`) is used by payment code/factory; `PayrollStatus` (`draft`, `approved`, `paid`) is cast by `TeacherPayroll` and used by payroll code/tests.

### Services and public methods

- `DashboardService`: `cards`, `trend`, `recentActivity`.
- `EnrollmentService` (source `EnrollmentServices.php`): `create`, `update`, `cancel`, `reactivate`.
- `PaymentService` (source `paymentService.php`): `record`.
- `PayrollService` (source `payrollServices.php`): `generate`, `approve`, `markAsPaid`.
- `TeacherReportService`: `get`; `TeacherPayrollReportService` (source `TeacherpayrollReportService.php`): `get`.
- `MonthlyStatisticsService` (source `MonthlyStatisticsServices.php`): `get`.
- `GeneralStatisticsService` (source `GeneralStatististicsService.php`): `get`, `totalStudents`.
- `FinancialReportService`: `getPeriodReport`, `getPaymentMethodBreakdown`.

### Controllers, actions, and requests

- `AcademicPeriodController`: `index`, `create`, `store` (`StoreAcademicPeriodRequest`), `edit`, `update` (`UpdateAcademicperiodRequest`), `destroy`.
- `FamilyController`: `index`, `create`, `store` (`StoreFamilyRequest`), `edit`, `update` (`UpdateFamilyRequest`), `destroy`.
- `GradeController`: `index`, `create`, `store` (`StoreGradeRequest`), `edit`, `update` (`UpdateGradeRequest`), `destroy`.
- `StageController`: `index`, `create`, `store` (`StoreStageRequest`), `edit`, `update` (`UpdateStageRequest`), `destroy`.
- `SubjectController`: `index`, `create`, `store` (`StoreSubjectRequest`), `edit`, `update` (`UpdateSubjectRequest`), `destroy`.
- `TeacherController`: `index`, `create`, `store` (`StoreTeacherRequest`), `edit`, `update` (`UpdateTeacherRequest`), `destroy`.
- `StudentController`: `index`, `create`, `store` (`StoreStudentRequest`), `edit`, `update` (imports absent `UpdateStudentRequest`; actual file/class is misspelled `UpdateStaudentRequest`), `destroy`; private `formData` loads family/stage/grade options.
- `ReportExportController`: `teacherPdf`, `teacherExcel`, `payrollPdf`, `payrollExcel`, `monthlyPdf`, `monthlyExcel`, `generalPdf`, `generalExcel`; no Form Requests, uses `Illuminate\Http\Request` for filter inputs.

Request fields generally map to migration columns. Student/teacher image uploads use input name `image`, then controllers save its path to `image_path`. The teacher migration requires `percentage` without a database default; confirm the store/update request requires it to prevent an insert error.

### Livewire components and render views

- `Dashboard` (`periodId`) → `livewire.dashboard`.
- `EnrollmentForm` (source `EnrollmentFrom.php`; properties enrollment, stageId, gradeId, studentId, teacherId, subjectId, periodId, durationType, feeAmount, supportAmount, notes) → `livewire.enrollment-form`.
- `EnrollmentsTable` (periodId, teacherId, subjectId, stageId, gradeId, status) → `livewire.enrollments-table`.
- `EnrollmentPayments` (source `EnrommentPayments.php`; enrollment, amount, paymentDate, paymentMethod, reference, notes) → `livewire.enrollment-payments`.
- `PayrollsIndex` (source `payrollsIndex.php`; periodId) → `livewire.payrolls-index`; `PayrollShow` (source `payrollShow.php`; payroll) → `livewire.payroll-show`.
- `StudentsTable` (search, stageId, gradeId) → `livewire.students-table`.
- Report namespace components: `TeacherReport` (periodId, teacherId, subjectId, stageId, gradeId) → `livewire.reports.teacher-report`; `TeacherPayrollReport` (periodId) → `livewire.reports.teacher-payroll-report`; `MonthlyStatistics` (periodId) → `livewire.reports.monthly-statistics`; `GeneralStatistics` (no filter properties) → `livewire.reports.general-statistics`. These namespace classes are stored in the wrong directory for PSR-4.

### Routes and route names

- `GET /` → `home` view, name `home`; then another `GET /` → `Dashboard`, also `home` (duplicate).
- `POST /logout` → missing `LogoutController`, name `logout`.
- `GET /reports` → `reports.index`; report pages `/reports/teacher`, `/reports/payroll`, `/reports/monthly`, `/reports/general` → respectively `reports.teacher`, `reports.payroll`, `reports.monthly`, `reports.general` and the similarly named report Livewire classes.
- `GET /payrolls` → `payrolls.index`; `GET /payrolls/{payroll}` → `payrolls.show`.
- Enrollment routes: `/enrollments` → `enrollments.index`; `/enrollments/create` → `enrollments.create`; `/enrollments/{enrollment}/edit` → `enrollments.edit`; `/enrollments/{enrollment}/payments` → `enrollments.payments`.
- Resource routes excluding `show`: `stages.*` → `StageController`; `grades.*` → `GradeController`; `subjects.*` → `SubjectController`; `academic-periods.*` → `AcademicPeriodController`; `students.*` → `StudentController`; `families.*` → `FamilyController`; `teachers.*` → `TeacherController`. Each has standard `index`, `create`, `store`, `edit`, `update`, and `destroy` names.
- Export routes: `reports.teacher.pdf`, `reports.teacher.excel`, `reports.payroll.pdf`, `reports.payroll.excel`, `reports.monthly.pdf`, `reports.monthly.excel`, `reports.general.pdf`, `reports.general.excel`, each targeting the same-named `ReportExportController` action.

### Tests

Feature files: `DashboardTest.php` (dashboard), `EnromentPaymentTest.php` (enrollment/payment), `PayrollManagementTest.php` (payroll), `ExampleTest.php` (framework example). Unit files: `DashboardServiceTest.php`, `GenerelStatisticsServiceTest.php`, `MonthlyStatisticsServiceTest.php`, `PallServiceTest.php` (payroll), `paymentServiceTest.php`, `TeacherPayrollReportServiceTest.php`, `TeacherReportServiceTest.php`, `ExampleTest.php`. `tests/TestCase.php` is the shared harness. Factories on disk: `UserFactory`, `TeacherFactory`, `StudentFactory`, `StageFactory`, `GradeFactory`, lowercase `paymentFactory`; missing `EnrollmentFactory`, `AcademicPeriodFactory`, and `FamilyFactory` are referenced by tests/factories.

### Migration order and schema summary

Migrations execute in this order; every FK target table is created earlier in the sequence, with no missing table dependency:

1. `0001_01_01_000000_create_users_table.php`: `users` (unique email), password reset token table, sessions table (indexed nullable `user_id`, no FK).
2. `0001_01_01_000001_create_cache_table.php`: cache and cache locks, string primary keys and indexed expirations.
3. `0001_01_01_000002_create_jobs_table.php`: jobs (indexed queue), job batches (string PK), failed jobs (unique UUID).
4. `2026_09_23_185411_create_stages_table.php`: stages (unique code, sort order, active flag).
5. `2026_09_23_185447_create_grades_table.php`: stage FK (restrict delete), name/code/order/active/notes; unique `(stage_id, code)`.
6. `2026_09_23_185547_create_families_table.php`: name, nullable phone/address/notes, active, timestamps and soft deletes.
7. `2026_09_23_185618_create_students_table.php`: family and grade FKs (restrict), unique student_code, contact/image/order/notes/active, timestamps and soft deletes.
8. `2026_09_23_185642_create_subjects_table.php`: unique code, name/order/active/notes, timestamps and soft deletes.
9. `2026_09_23_185656_create_teachers_table.php`: name/contact/image/order, required decimal percentage, active/notes, timestamps and soft deletes.
10. `2026_09_23_185741_create_academic_periods_table.php`: year/month/name/date range/closed; unique `(year, month)`.
11. `2026_09_23_185801_create_enrollments_table.php`: student/teacher/subject/period FKs (restrict), duration, fee/support/status/notes; unique `(student_id, subject_id, period_id)`; indexes `(period_id, teacher_id)` and status.
12. `2026_09_23_185817_create_payments_table.php`: enrollment and received_by user FKs (restrict), amount/date/method/reference/notes; payment date index.
13. `2026_09_23_185850_create_teacher_payrolls_table.php`: period and creator user FKs (restrict), status and generated/approved/paid timestamps, notes.
14. `2026_09_23_185931_create_teacher_payroll_items_table.php`: payroll and teacher FKs (restrict), count and amounts/percentages; unique `(payroll_id, teacher_id)`.

Model detail (fillable | casts | declared relationships):

| Model | `$fillable` | `casts()` | Relationships |
|---|---|---|---|
| `AcademicPeriod` | year, month, name, starts_at, ends_at, is_closed | year/month integer; starts_at/ends_at date; is_closed boolean | enrollments, teacherPayrolls |
| `Enrollment` | student_id, teacher_id, subject_id, period_id, duration_type, fee_amount, support_amount, status, notes | duration_type `DurationType`; fee/support decimal:2; status `EnrollmentStatus` | student, teacher, subject, academicPeriod, payments (target missing `Payment`) |
| `Family` | name, phone, address, notes, is_active | is_active boolean | students |
| `Grade` | stage_id, name, code, sort_order, is_active, notes | sort_order integer; is_active boolean | stage (target missing `Stage`), students |
| `Student` | family_id, grade_id, student_code, name, sort_order, phone, image_path, notes, is_active | sort_order integer; is_active boolean | family, grade, enrollments |
| `Subject` | name, code, sort_order, is_active, notes | sort_order integer; is_active boolean | enrollments |
| `Teacher` | name, phone, email, image_path, sort_order, percentage, is_active, notes | sort_order integer; percentage decimal:2; is_active boolean | enrollments, payrollItems |
| `TeacherPayroll` | period_id, status, generated_at, approved_at, paid_at, created_by, notes | status `PayrollStatus`; timestamps datetime | academicPeriod, creator, items |
| `TeacherPayrollItem` | payroll_id, teacher_id, students_count, fee_amount, support_amount, gross_amount, center_percentage, center_amount, teacher_amount | students_count integer; monetary and percentage values decimal:2 | payroll, teacher |
| `User` | name, email, password | email_verified_at datetime; password hashed | receivedPayments (target missing `Payment`), createdPayrolls |

No Stage/Payment model entries exist to report. Models also contain query scopes/accessors/photo URL helpers; only Eloquent relationships are listed in the final column.
