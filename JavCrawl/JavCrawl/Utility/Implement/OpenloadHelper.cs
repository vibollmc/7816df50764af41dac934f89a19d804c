using JavCrawl.Models;
using JavCrawl.Models.Openload;
using JavCrawl.Utility.Context;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.Options;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Threading.Tasks;

namespace JavCrawl.Utility.Implement
{
    public class OpenloadHelper : IOpenloadHelper
    {
        private readonly OpenloadSettings _openloadSettings;
        public OpenloadHelper(IOptions<OpenloadSettings> openloadSettings)
        {
            _openloadSettings = openloadSettings.Value;
        }

        public async Task<int> RemoteFile(string fileUrl)
        {
            var url = string.Format(_openloadSettings.ApiLinkRemoteFile, fileUrl);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return 0;

            var result = JsonConvert.DeserializeObject<OpenloadResult<RemoteResult>>(json);

            if (result == null || 
                result.status != ResultStatus.Success || 
                result.result == null || 
                string.IsNullOrWhiteSpace(result.result.id)) return 0;

            return int.Parse(result.result.id);
        }

        public async Task<string> RemoteFileStatus(int idRemote)
        {
            var url = string.Format(_openloadSettings.ApiLinkRemoteStatus, idRemote);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return null;

            var result = JsonConvert.DeserializeObject<OpenloadResult<dynamic>>(json);

            if (result == null ||
                result.status != ResultStatus.Success || result.result == null) return null;

            if (result.result[0].status == "finished")
            {
                return result.result[0].url;
            }
            return null;
        }

        public async Task<bool> RenameFile(string fileId, string newName)
        {
            var url = string.Format(_openloadSettings.ApiLinkRenameFile, fileId, newName);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return false;

            var result = JsonConvert.DeserializeObject<OpenloadResult<bool>>(json);

            if (result == null ||
                result.status != ResultStatus.Success) return false;

            return result.result;
        }
    }
}
