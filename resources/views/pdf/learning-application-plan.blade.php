<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 20px 30px; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #000; }

        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .header-table td { vertical-align: middle; padding: 0; }
        .logo-cell { width: 70px; }
        .logo-cell img { width: 60px; height: 60px; }
        .agency-name { font-size: 9px; color: #2e7d32; line-height: 1.2; }
        .city-name { font-size: 16px; font-weight: bold; color: #1a5c1a; }
        .doc-code { text-align: right; font-size: 9px; line-height: 1.3; }

        .committee-bar {
            background: #1a5c1a;
            color: #fff;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .form-title { text-align: center; font-weight: bold; font-size: 12px; margin-bottom: 2px; }
        .form-subtitle { text-align: center; font-size: 11px; margin-bottom: 12px; }

        .field-row { margin-bottom: 4px; }
        .field-label { font-weight: bold; display: inline-block; }
        .field-line { border-bottom: 1px solid #000; display: inline-block; padding-bottom: 1px; }

        .checkbox-row { margin: 8px 0; }
        .checkbox { display: inline-block; width: 9px; height: 9px; border: 1px solid #000; margin-right: 3px; text-align: center; font-size: 8px; line-height: 9px; }
        .checked { background: #000; color: #fff; }

        table.grid { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.grid td { border: 1px solid #000; vertical-align: top; padding: 6px; width: 50%; }

        .box-title { font-weight: bold; text-align: center; margin-bottom: 6px; font-size: 10px; }
        .box-item { margin-bottom: 3px; }

        .insight-box { border: 1px solid #000; padding: 8px; margin-top: 8px; min-height: 60px; }
        .insight-label { font-weight: bold; }
        .insight-lines { border-bottom: 1px solid #000; display: block; min-height: 14px; margin-top: 4px; }

        .full-width-box { border: 1px solid #000; padding: 6px; }
        .full-width-box.top-only { border-bottom: none; }

        .signature-table { width: 100%; margin-top: 30px; }
        .signature-table td { text-align: center; font-size: 9px; padding-top: 30px; }
        .sig-line { border-top: 1px solid #000; padding-top: 3px; }

        .footer { position: fixed; bottom: -10px; left: 0; right: 0; font-size: 8px; text-align: center; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                {{-- Palitan ng absolute path ng logo image mo --}}
                {{-- <img src="{{ public_path('images/tagum-logo.png') }}"> --}}
            </td>
            <td>
                <div class="agency-name">REPUBLIC OF THE PHILIPPINES<br>PROVINCE OF DAVAO DEL NORTE</div>
                <div class="city-name">CITY OF TAGUM</div>
            </td>
            <td class="doc-code">
                F-HRM-HRD-0006<br>
                Revision : 03<br>
                Mar 2025
            </td>
        </tr>
    </table>

    <div class="committee-bar">PERSONNEL DEVELOPMENT COMMITTEE</div>

    <div class="form-title">Learning Application Plan</div>
    <div class="form-subtitle">(LAP Form)</div>

    {{-- BASIC INFO --}}
    <div class="field-row">
        <span class="field-label">Office:</span>
        <span class="field-line">{{ $plan->office ?? '' }}</span>
    </div>
    <div class="field-row">
        <span class="field-label">Learner:</span>
        <span class="field-line">{{ $plan->learner ?? '' }}</span>
    </div>
    <div class="field-row">
        <span class="field-label">Title of Intervention:</span>
        <span class="field-line">{{ $plan->title_of_intervention ?? '' }}</span>
    </div>
    <div class="field-row">
        <span class="field-label">Date Conducted:</span>
        <span class="field-line">{{ $plan->date_conducted ?? '' }}</span>
    </div>
    <div class="field-row">
        <span class="field-label">Venue:</span>
        <span class="field-line">{{ $plan->venue ?? '' }}</span>
    </div>

    {{-- COMPETENCY CATEGORY CHECKBOXES --}}
    <div class="checkbox-row">
        <span class="field-label">Competency Category:</span>
        <span class="checkbox {{ $plan->foundation ? 'checked' : '' }}">{{ $plan->foundation ? 'X' : '' }}</span> Foundation
        &nbsp;&nbsp;
        <span class="checkbox {{ $plan->techinal ? 'checked' : '' }}">{{ $plan->techinal ? 'X' : '' }}</span> Technical
        &nbsp;&nbsp;
        <span class="checkbox {{ $plan->supervisory ? 'checked' : '' }}">{{ $plan->supervisory ? 'X' : '' }}</span> Supervisory
        &nbsp;&nbsp;
        <span class="checkbox {{ $plan->managerial ? 'checked' : '' }}">{{ $plan->managerial ? 'X' : '' }}</span> Managerial
    </div>
    <div style="font-weight: bold; margin-bottom: 4px;">Competency Developed/Acquired</div>

    {{-- COMPETENCY GRID --}}
    <table class="grid">
        <tr>
            <td>
                <div class="box-title">Foundation Competencies</div>
                <div class="box-item">
                    <span class="checkbox {{ $foundation->delivering_service_excellence ?? false ? 'checked' : '' }}">{{ ($foundation->delivering_service_excellence ?? false) ? 'X' : '' }}</span>
                    Delivering Service Excellence
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $foundation->exemplifying_integrity ?? false ? 'checked' : '' }}">{{ ($foundation->exemplifying_integrity ?? false) ? 'X' : '' }}</span>
                    Exemplifying Integrity
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $foundation->interpersonal_skills ?? false ? 'checked' : '' }}">{{ ($foundation->interpersonal_skills ?? false) ? 'X' : '' }}</span>
                    Interpersonal Skills
                </div>
            </td>
            <td>
                <div class="box-title">Technical Competencies</div>
                <div class="box-item">
                    <span class="checkbox {{ $technical->planning_organizing ?? false ? 'checked' : '' }}">{{ ($technical->planning_organizing ?? false) ? 'X' : '' }}</span>
                    Planning and Organizing
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $technical->monitoring_evaluation ?? false ? 'checked' : '' }}">{{ ($technical->monitoring_evaluation ?? false) ? 'X' : '' }}</span>
                    Monitoring and Evaluation
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $technical->records_management ?? false ? 'checked' : '' }}">{{ ($technical->records_management ?? false) ? 'X' : '' }}</span>
                    Records Management
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $technical->partnering_networking ?? false ? 'checked' : '' }}">{{ ($technical->partnering_networking ?? false) ? 'X' : '' }}</span>
                    Partnering and Networking
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $technical->process_management ?? false ? 'checked' : '' }}">{{ ($technical->process_management ?? false) ? 'X' : '' }}</span>
                    Process Management
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $technical->attention_detail ?? false ? 'checked' : '' }}">{{ ($technical->attention_detail ?? false) ? 'X' : '' }}</span>
                    Attention to Detail
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="box-title">Supervisory Competencies</div>
                <div class="box-item">
                    <span class="checkbox {{ $supervisory->supervisory_managing_performance_coaching_results ?? false ? 'checked' : '' }}">{{ ($supervisory->supervisory_managing_performance_coaching_results ?? false) ? 'X' : '' }}</span>
                    Managing Performance and Coaching Results
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $supervisory->supervisory_building_collaborative_inclusive_working_relationships ?? false ? 'checked' : '' }}">{{ ($supervisory->supervisory_building_collaborative_inclusive_working_relationships ?? false) ? 'X' : '' }}</span>
                    Building Collaborative and Inclusive Working Relationships
                </div>
            </td>
            <td>
                <div class="box-title">Managerial Competencies</div>
                <div class="box-item">
                    <span class="checkbox {{ $managerial->managing_performance_coaching_results ?? false ? 'checked' : '' }}">{{ ($managerial->managing_performance_coaching_results ?? false) ? 'X' : '' }}</span>
                    Managing Performance and Coaching Results
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $managerial->building_collaborative_inclusive_working_relationships ?? false ? 'checked' : '' }}">{{ ($managerial->building_collaborative_inclusive_working_relationships ?? false) ? 'X' : '' }}</span>
                    Building Collaborative and Inclusive Working Relationships
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $managerial->thinking_strategically_creatively ?? false ? 'checked' : '' }}">{{ ($managerial->thinking_strategically_creatively ?? false) ? 'X' : '' }}</span>
                    Thinking Strategically and Creatively
                </div>
                <div class="box-item">
                    <span class="checkbox {{ $managerial->problem_solving_decision_making ?? false ? 'checked' : '' }}">{{ ($managerial->problem_solving_decision_making ?? false) ? 'X' : '' }}</span>
                    Problem Solving and Decision Making
                </div>
            </td>
        </tr>
    </table>

    {{-- SIGNIFICANT LEARNING INSIGHT --}}
    <div class="insight-box">
        <span class="insight-label">Significant Learning/Insight:</span>
        <div class="insight-lines">{{ $plan->significant_learning_insight ?? '' }}</div>
        <div class="insight-lines">&nbsp;</div>
        <div class="insight-lines">&nbsp;</div>
    </div>

    {{-- STRATEGIES / KPI GRID --}}
    <table class="grid">
        <tr>
            <td>
                <div class="box-title" style="text-align:left;">Learning Strategies to be implemented</div>
                <div class="box-item"><span class="checkbox {{ $strategies->immediate_application_skills ?? false ? 'checked' : '' }}">{{ ($strategies->immediate_application_skills ?? false) ? 'X' : '' }}</span> Immediate Application of new skills acquired</div>
                <div class="box-item"><span class="checkbox {{ $strategies->knowledge_sharing ?? false ? 'checked' : '' }}">{{ ($strategies->knowledge_sharing ?? false) ? 'X' : '' }}</span> Knowledge Sharing</div>
                <div class="box-item"><span class="checkbox {{ $strategies->peer_coaching_collaboration ?? false ? 'checked' : '' }}">{{ ($strategies->peer_coaching_collaboration ?? false) ? 'X' : '' }}</span> Peer Coaching and Collaboration</div>
                <div class="box-item"><span class="checkbox {{ $strategies->develop_office_policies_guidelines ?? false ? 'checked' : '' }}">{{ ($strategies->develop_office_policies_guidelines ?? false) ? 'X' : '' }}</span> Develop office policies and guidelines</div>
                <div class="box-item"><span class="checkbox {{ $strategies->create_pilot_project ?? false ? 'checked' : '' }}">{{ ($strategies->create_pilot_project ?? false) ? 'X' : '' }}</span> Create a Pilot Project</div>
                <div class="box-item"><span class="checkbox {{ $strategies->include_ipcr ?? false ? 'checked' : '' }}">{{ ($strategies->include_ipcr ?? false) ? 'X' : '' }}</span> Include in the IPCR</div>
            </td>
            <td>
                <div class="box-title" style="text-align:left;">Key Performance Indicator (based on the IPCR)</div>
                <div class="box-item"><span class="checkbox {{ $indicator->strategic_functions ?? false ? 'checked' : '' }}">{{ ($indicator->strategic_functions ?? false) ? 'X' : '' }}</span> Strategic Functions</div>
                <div class="box-item"><span class="checkbox {{ $indicator->core_functions ?? false ? 'checked' : '' }}">{{ ($indicator->core_functions ?? false) ? 'X' : '' }}</span> Core Functions</div>
                <div class="box-item"><span class="checkbox {{ $indicator->support_functions ?? false ? 'checked' : '' }}">{{ ($indicator->support_functions ?? false) ? 'X' : '' }}</span> Support Functions</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="box-title" style="text-align:left;">Resources to be utilized</div>
                <div class="box-item"><span class="checkbox {{ $resources->digital_technologies ?? false ? 'checked' : '' }}">{{ ($resources->digital_technologies ?? false) ? 'X' : '' }}</span> Digital & Technologies</div>
                <div class="box-item"><span class="checkbox {{ $resources->physical_printed_resources ?? false ? 'checked' : '' }}">{{ ($resources->physical_printed_resources ?? false) ? 'X' : '' }}</span> Physical & Printed Resources</div>
                <div class="box-item"><span class="checkbox {{ $resources->human_resources_organizational_support ?? false ? 'checked' : '' }}">{{ ($resources->human_resources_organizational_support ?? false) ? 'X' : '' }}</span> Human Resources & Organizational Support</div>
                <div class="box-item"><span class="checkbox {{ $resources->financial_logistical_support ?? false ? 'checked' : '' }}">{{ ($resources->financial_logistical_support ?? false) ? 'X' : '' }}</span> Financial & Logistical Support</div>
                <div class="box-item"><span class="checkbox {{ $resources->policy_process_resources ?? false ? 'checked' : '' }}">{{ ($resources->policy_process_resources ?? false) ? 'X' : '' }}</span> Policy & Process Resources</div>
            </td>
            <td>
                <div class="box-title" style="text-align:left;">Beneficiaries of the Strategies Applied</div>
                <div class="box-item"><span class="checkbox {{ $beneficiaries->employees_staff ?? false ? 'checked' : '' }}">{{ ($beneficiaries->employees_staff ?? false) ? 'X' : '' }}</span> Employees and Staff</div>
                <div class="box-item"><span class="checkbox {{ false }}"></span> Supervisors and Managers</div>
                <div class="box-item"><span class="checkbox {{ $beneficiaries->office_department ?? false ? 'checked' : '' }}">{{ ($beneficiaries->office_department ?? false) ? 'X' : '' }}</span> The Office/Department</div>
                <div class="box-item"><span class="checkbox {{ $beneficiaries->city_government_organization ?? false ? 'checked' : '' }}">{{ ($beneficiaries->city_government_organization ?? false) ? 'X' : '' }}</span> The City Government/Organization</div>
                <div class="box-item"><span class="checkbox {{ $beneficiaries->clients_stakeholders_general_public ?? false ? 'checked' : '' }}">{{ ($beneficiaries->clients_stakeholders_general_public ?? false) ? 'X' : '' }}</span> Clients, Stakeholders & the General Public</div>
            </td>
        </tr>
    </table>

    {{-- TARGET DATE OF COMPLETION --}}
    <div class="full-width-box" style="margin-top: 8px;">
        <div class="box-title">Target Date of Completion</div>
        <div class="box-item"><span class="checkbox {{ $target->within_2_weeks_after_training ?? false ? 'checked' : '' }}">{{ ($target->within_2_weeks_after_training ?? false) ? 'X' : '' }}</span> Within 2 weeks after the training</div>
        <div class="box-item"><span class="checkbox {{ $target->within_1_month_after_training ?? false ? 'checked' : '' }}">{{ ($target->within_1_month_after_training ?? false) ? 'X' : '' }}</span> Within 1 month after the training</div>
        <div class="box-item"><span class="checkbox {{ $target->within_2_months_after_training ?? false ? 'checked' : '' }}">{{ ($target->within_2_months_after_training ?? false) ? 'X' : '' }}</span> Within 2 months after the training</div>
        <div class="box-item"><span class="checkbox {{ $target->within_3_months_after_training ?? false ? 'checked' : '' }}">{{ ($target->within_3_months_after_training ?? false) ? 'X' : '' }}</span> Within 3 months after the training</div>
    </div>

    {{-- SIGNATURES --}}
    <table class="signature-table">
        <tr>
            <td><div class="sig-line">Learner</div></td>
            <td><div class="sig-line">Supervisor</div></td>
            <td><div class="sig-line">Head of Office</div></td>
        </tr>
    </table>
    <div class="field-row" style="margin-top: 10px;">
        <span class="field-label">Date:</span>
        <span class="field-line">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    </div>

    <div class="footer">
        2nd Floor, City Hall of Tagum, JV Ayala Ave., Brgy. Apokon &nbsp;|&nbsp; (084) 645 3300 Local 216-220, 252 &nbsp;|&nbsp; tagumcityhumanresource@gmail.com
    </div>

</body>
</html>