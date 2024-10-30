{
    "title": "Rolle og grupper - Lærefag ({{ getenv "CANVAS_ACCESS_KEY_NAME" }})",
    "description": "Rolle og grupper - Lærefag ({{ getenv "CANVAS_ACCESS_KEY_NAME" }})",
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
                        "placement": "course_navigation",
                        "message_type": "LtiResourceLinkRequest",
                        "icon_url": "https://icons.iconarchive.com/icons/papirus-team/papirus-places/64/folder-blue-linux-icon.png",
                        "target_link_uri": "{{ getenv "APP_URL" }}/launch?config_directory=configs",
                        "text": "Rolle og grupper - Lærefag ({{ getenv "CANVAS_ACCESS_KEY_NAME" }})",
                        "selection_width": 800,
                        "selection_height": 800
                    },
                    {
                        "placement": "editor_button",
                        "message_type": "LtiDeepLinkingRequest",
                        "icon_url": "https://icons.iconarchive.com/icons/papirus-team/papirus-places/64/folder-blue-linux-icon.png",
                        "target_link_uri": "{{ getenv "APP_URL" }}/launch?config_directory=configs",
                        "text": "Rolle og grupper - Lærefag ({{ getenv "CANVAS_ACCESS_KEY_NAME" }})",
                        "selection_height": 800,
                        "selection_width": 800
                    }
                ]
            }
        }
    ],
    "custom_fields": {
        "FACULTY_OPTION_1": "Bygg- og anleggsteknikk",
        "FACULTY_OPTION_2": "Elektro og datateknologi",
        "FACULTY_OPTION_3": "Frisør, blomster, interiør og eksponeringsdesign",
        "FACULTY_OPTION_4": "Helse- og oppvekstfag",
        "FACULTY_OPTION_5": "Håndverk, design og produktutvikling",
        "FACULTY_OPTION_6": "Informasjonsteknologi og medieproduksjon",
        "FACULTY_OPTION_7": "Naturbruk",
        "FACULTY_OPTION_8": "Restaurant- og matfag",
        "FACULTY_OPTION_9": "Salg, service og reiseliv",
        "FACULTY_OPTION_10": "Teknologi- og industrifag",
        "custom_canvas_roles": "$Canvas.membership.roles",
        "custom_canvas_user_id": "$Canvas.user.id",
        "custom_canvas_course_id": "$Canvas.course.id",
        "custom_canvas_account_id": "$Canvas.account.id",
        "custom_canvas_course_name": "$Canvas.course.name",
        "institution_leader_description": "Ansatt i fylkeskommunen",
        "custom_canvas_user_display_name": "$Person.name.display",
        "institution_participant_description": "Annen rolle i fagopplæringen"
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
