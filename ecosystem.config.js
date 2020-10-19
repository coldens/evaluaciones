module.exports = {
    apps: [
        {
            name: "evaluations-queue",
            exec_mode: "fork",
            interpreter: "php",
            instances: "-1",
            script: "artisan",
            args: "queue:work -v --tries=5 --timeout=60 --delay=60",
            autorestart: true,
        },
    ],
};
