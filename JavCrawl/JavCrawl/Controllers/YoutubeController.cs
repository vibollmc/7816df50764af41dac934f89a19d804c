using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using JavCrawl.Utility.Context;
using JavCrawl.Models;
using Microsoft.Extensions.Options;

namespace JavCrawl.Controllers
{
    public class YoutubeController : Controller
    {
        private readonly IYoutubeHelper _youtubeHelper;
        private readonly YoutubeSettings _youtubeSettings;
        public YoutubeController(IYoutubeHelper youtubeHelper, IOptions<YoutubeSettings> youtubeSettings)
        {
            _youtubeHelper = youtubeHelper;
            _youtubeSettings = youtubeSettings.Value;
        }
        public async Task<IActionResult> Index(string keyword, int? max, IList<string> video)
        {
            if (video != null && video.Count > 0)
            {
                await _youtubeHelper.Comment(video, _youtubeSettings.CommentDefault);
            }

            IList<YoutubeVideo> model = null;

            ViewBag.Keyword = keyword;
            ViewBag.max = (max == null || max <= 0) ? 50 : max.Value;

            if (!string.IsNullOrWhiteSpace(keyword) && max != null && max > 0)
            {
                model = await _youtubeHelper.Search(keyword, max.Value, null, null, null, null);
            }

            return View(model);
        }
    }
}