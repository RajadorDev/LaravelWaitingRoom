
var keepAliveStarted: boolean = false;

async function keepAlive() : Promise<void> {
    const URL = import.meta.env.VITE_APP_URL + '/api/keepalive';
    const response = await fetch(URL);

    if (response.ok) {
        return;
    }

    throw `Error while trying to keep alive, HTTP Code: ${response.status}`;
} 

function runKeepAlive(updateTimeMs: number) : void {
    setTimeout(
        function () : void {
            keepAlive().then (
                () => runKeepAlive(updateTimeMs)
            ).catch(
                (error) => {
                    console.error(error);
                    runKeepAlive(updateTimeMs);
                }
            )
        },
        updateTimeMs
    );
}

export default function startKeepAlive(updateTimeMs: number) : void {
    if (keepAliveStarted) {
        throw 'Keep alive is already startted';
    }
    keepAliveStarted = true;
    runKeepAlive(updateTimeMs);
}

/**
 * 
 * @param updateMileseconds 
 */
export function tryToStartKeepAlive(updateMileseconds: number) : boolean {
    try {
        startKeepAlive(updateMileseconds);
        return true;
    } catch (error) {
        return false;
    }
}