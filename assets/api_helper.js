import axios from 'axios';
import {alertError, addModalIntoBodyTag} from "@Common";
import {alertError} from "@Common";
const axiosGet = (url, listCallBack) => {
    const { success, failed, final } = listCallBack;
    axios.get(url)
        .then( res => {
            if(success === undefined) return;
            success(res.data);
        }).catch(xhr => {
            if(failed === undefined){
                alertError();
                return;
            }
            failed(xhr.response.data);
    }).finally(_ => {
        if(final === undefined) return;
        final();
    })
}

const axiosPost = (options, listCallBack) => {
    const { url, data, headers } = options;
    const { success, failed, final } = listCallBack;
    axios.post(url, data, headers)
        .then( res => {
            if(success === undefined) return;
            success(res.data);
        }).catch(xhr => {
        if(failed === undefined){
            alertError();
            return;
        }
        failed(xhr.response.data);
    }).finally(_ => {
        if(final === undefined) return;
        final();
    })
}

export {axiosGet, axiosPost};