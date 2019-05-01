using Football.Show.Dal.Context;
using Football.Show.Entities;
using Football.Show.Entities.Enum;
using Microsoft.EntityFrameworkCore;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.Dal.Implement
{
    public class CodePlugInRepository : ICodePlugInRepository
    {
        public readonly MainDbContext _dbContext;
        public CodePlugInRepository(LoadDbContext loadDbContext)
        {
            _dbContext = loadDbContext.DbContext;
        }

        public async Task<IEnumerable<CodePlugIn>> GetCodePlugIns(CodeType codeType)
        {
            return await _dbContext.CodePlugIns
                .Where(x => !x.DeletedAt.HasValue && x.CodeType == codeType)
                .ToListAsync();
        }
    }
}
