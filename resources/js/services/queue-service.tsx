

export type QueueInfo = {
    position: number,
    updateMileseconds: number
};

export async function heartbeat() : Promise<QueueInfo> {

    const result = await fetch('api/queue/heartbeat');

    if (result.ok) {
        const jsonData = await result.json();
        return jsonData;
    }
    throw 'Error while trying to do a heartbeat, code: ' + result.status;
}


export function transferToTarget() : void {
    const params = new URLSearchParams(window.location.search);
    const target = params.get('target') ?? '/dashboard';
    window.location.href = target;
}

export function getTargetPageName() : string {
    const defaultName = 'Dashboard';
    if (window !== undefined) {
        const params = new URLSearchParams(window.location.search);
        return params.get('targetName') ?? defaultName;
    } 
    return defaultName;
}