{
    "title": "Emneinnstillinger ({{ getenv "CANVAS_ACCESS_KEY_NAME" }})",
    "description": "Emneinnstillinger ({{ getenv "CANVAS_ACCESS_KEY_NAME" }})",
    "target_link_uri": "{{ getenv "APP_URL" }}/launch?config_directory=configs",
    "oidc_initiation_url": "{{ getenv "APP_URL" }}/lti3?config_directory=configs",
    "extensions": [
        {
            "privacy_level": "public",
            "platform": "canvas.instructure.com",
            "settings": {
                "platform": "canvas.instructure.com",
                "placements": [
                    {
                        "placement": "course_settings_sub_navigation",
                        "message_type": "LtiResourceLinkRequest",
                        "icon_url": "https://icons.iconarchive.com/icons/papirus-team/papirus-places/64/folder-blue-linux-icon.png",
                        "target_link_uri": "{{ getenv "APP_URL" }}/launch?config_directory=configs&kpasMode=6",
                        "text": "Emneinnstillinger ({{ getenv "CANVAS_ACCESS_KEY_NAME" }})",
                        "selection_width": 800,
                        "selection_height": 800
                    }
                ]
            }
        }
    ],
    "custom_fields": {
        "FACULTY_OPTION_1": "Matematikk 1-7",
        "FACULTY_OPTION_2": "Matematikk 8-10",
        "FACULTY_OPTION_3": "Naturfag 1-7",
        "FACULTY_OPTION_4": "Naturfag 8-10",
        "FACULTY_OPTION_5": "Kunst & Håndverk 1-10",
        "FACULTY_OPTION_6": "Musikk 1-10",
        "custom_canvas_roles": "$Canvas.membership.roles",
        "county_category_name": "Fylke",
        "school_category_name": "Skole",
        "custom_canvas_user_id": "$Canvas.user.id",
        "community_category_name": "Kommune",
        "custom_canvas_course_id": "$Canvas.course.id",
        "custom_canvas_account_id": "$Canvas.account.id",
        "custom_canvas_course_name": "$Canvas.course.name",
        "county_faculty_category_name": "Faggruppe kommune",
        "community_faculty_category_name": "Faggruppe fylke",
        "county_principals_category_name": "Leder/eier (fylke)",
        "custom_canvas_user_display_name": "$Person.name.display",
        "community_principals_category_name": "Leder/eier (kommune)"
    },
    "public_jwk": {{ getenv "PUBLIC_JWK_JSON" }},
    "scopes": [
        "https://purl.imsglobal.org/spec/lti-ags/scope/lineitem",
        "https://purl.imsglobal.org/spec/lti-ags/scope/lineitem.readonly",
        "https://purl.imsglobal.org/spec/lti-ags/scope/result.readonly",
        "https://purl.imsglobal.org/spec/lti-ags/scope/score",
        "https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly",
        "https://canvas.instructure.com/lti/public_jwk/scope/update",
        "https://canvas.instructure.com/lti/account_lookup/scope/show",
        "https://canvas.instructure.com/lti/data_services/scope/create",
        "https://canvas.instructure.com/lti/data_services/scope/show",
        "https://canvas.instructure.com/lti/data_services/scope/update",
        "https://canvas.instructure.com/lti/data_services/scope/destroy",
        "https://canvas.instructure.com/lti/data_services/scope/list",
        "https://canvas.instructure.com/lti/data_services/scope/list_event_types",
        "https://canvas.instructure.com/lti/feature_flags/scope/show"
    ]
}
