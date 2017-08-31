﻿using JavCrawl.Models;
using JavCrawl.Models.Openload;
using JavCrawl.Utility.Context;
using Microsoft.Extensions.Options;
using Newtonsoft.Json;
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

        public async Task<string> RemoteFile(string fileUrl)
        {
            var url = string.Format(_openloadSettings.ApiLinkRemoteFile, fileUrl);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return null;

            var result = JsonConvert.DeserializeObject<OpenloadResult<RemoteResult>>(json);

            if (result == null || 
                result.status != ResultStatus.Success || 
                result.result == null || 
                string.IsNullOrWhiteSpace(result.result.id)) return null;

            return result.result.id;
        }

        public async Task<string> RemoteFileStatus(string idRemote, int filmId)
        {
            var fileId = await CheckStatusRemote(idRemote);

            if (string.IsNullOrWhiteSpace(fileId)) return null;

            var fileName = string.Format("javmile.com-{0}.mp4", filmId);

            await RenameFile(fileId, fileName);

            return string.Format(_openloadSettings.LinkEmbed, fileId, fileName);
        }

        private async Task<string> CheckStatusRemote(string idRemote)
        {
            var url = string.Format(_openloadSettings.ApiLinkRemoteStatus, idRemote);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return null;

            var result = JsonConvert.DeserializeObject<OpenloadResult<dynamic>>(json);

            if (result == null ||
                result.status != ResultStatus.Success || result.result == null) return null;

            var jobject = result.result as Newtonsoft.Json.Linq.JObject;

            if (jobject.Count == 0) return null;

            var jproperty = jobject.First as Newtonsoft.Json.Linq.JProperty;

            var statusResults = JsonConvert.DeserializeObject<RemoteStatusResult>(jproperty.First.ToString());

            if (statusResults.status == "finished")
            {
                return statusResults.extid;
            }
            else if (statusResults.status == "error")
            {
                return "error";
            }

            return null;
        }

        private async Task<bool> RenameFile(string fileId, string newName)
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
