module.exports = function(name, params, callback)
{
    let command = 'php console.php ' + name,
        child   = require('child_process');

    for (let key in (params || []))
    {
        if (!params.hasOwnProperty(key))
        {
            continue;
        }

        if (params[key] === true)
        {
            command += ' --' + key;
        }
        else
        {
            command += ' --' + key + '="' + params[key] + '"';
        }
    }

    let response = child.execSync(command);

    try
    {
        response = JSON.parse(response);
    }
    catch(ex)
    {
        console.log('ERROR: Failed to parse json response.');
        console.log(response);

        response = {
            success: false,
            message: 'Failed to parse json response.'
        };
    }

    return response;
};