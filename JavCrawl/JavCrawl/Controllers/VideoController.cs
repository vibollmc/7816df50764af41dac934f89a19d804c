using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using JavCrawl.Utility.Context;

namespace JavCrawl.Controllers
{
    public class VideoController : Controller
    {
        private readonly IHtmlHelper _htmlHelper;
        public VideoController(IHtmlHelper htmlHelper)
        {
            _htmlHelper = htmlHelper;
        }
        public async Task<IActionResult> Index(string link)
        {
            var model = await _htmlHelper.GetRedirectLinkVideo(link);

            return Json(model);
        }
    }
}