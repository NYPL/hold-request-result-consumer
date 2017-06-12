const spawn = require('child_process').spawnSync;

const libraryPath = process.env['LD_LIBRARY_PATH'];

exports.handler = function(event, context, callback) {
    // console.log('CONTEXT');
    // console.log(context);
    // console.log('EVENT');
    // console.log(event);
    // console.log('RECORDS');
    // console.log(JSON.stringify(event.Records));

    if (event.Records) {
        var headers = {
            LD_LIBRARY_PATH: libraryPath
        };

        var log = {
            message: 'Starting processing',
            numberRecords: event.Records.length,
            streamArn: event.Records[0].eventSourceARN
        };

        console.log(JSON.stringify(log));

        var options = {
            input: JSON.stringify(event),
            env: Object.assign(process.env)
        };

        if (process.env.LAMBDA_TASK_ROOT) {
            var php = spawn('./php-cgi', ['-n', '-d expose_php=Off', 'listener.php'], options);
        } else {
            var php = spawn('php-cgi', ['-d expose_php=Off', 'listener.php'], options);
        }

        if (php.stderr.length) {
            php.stderr.toString().split("\n").map(function (message) {
                if (message.trim().length) console.log(message);
            });
        }

        callback(null, {body: php.stdout.toString()});
    }
};
