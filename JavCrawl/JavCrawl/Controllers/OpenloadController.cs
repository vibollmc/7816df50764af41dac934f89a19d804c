using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using JavCrawl.Utility.Context;
using JavCrawl.Dal.Context;
using JavCrawl.Models;

namespace JavCrawl.Controllers
{
    public class OpenloadController : Controller
    {
        private readonly IOpenloadHelper _openloadHelper;
        private readonly IDbRepository _dbRepository;

        public OpenloadController(IOpenloadHelper openloadHelper, IDbRepository dbRepository)
        {
            _openloadHelper = openloadHelper;
            _dbRepository = dbRepository;
        }
        [HttpGet]
        public IActionResult Index()
        {
            var model = new OpenloadView();

            model.Remoted = _dbRepository.GetEpisodesRemoted();
            model.Remoting = _dbRepository.GetEpisodesRemoting();

            return View(model);
        }
        //[HttpPost]
        //public async Task<IActionResult> Index(string link)
        //{
        //    ViewBag.Message = string.Empty;
        //    if (!string.IsNullOrWhiteSpace(link))
        //    {
        //        var idRemote = await _openloadHelper.RemoteFile(link);

        //        ViewBag.Message = "Remote successfuly. (id=" + idRemote + ")";
        //        //Remote successfuly. (id=74871634) 
        //    }
        //    return View();
        //}
    }
}